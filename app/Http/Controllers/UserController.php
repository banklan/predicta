<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserEmailConfirmation;
use App\User;
use App\Enquiry;
use App\Plan;
use App\Mail\EnquirySent;
use App\Mail\EnquiryMail;
use App\Payment;
use App\Subscription;
use App\Expert;
use App\ExpertFollow;
use Image;
use App\MailingList;
use Illuminate\Support\Facades\Hash;
use App\UsersFeedback;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackPostedEmail;
use phpDocumentor\Reflection\PseudoTypes\False_;

class UserController extends Controller
{
    public function confirmEmail(Request $request){
        $token = $request->token;

        $conf = UserEmailConfirmation::where('token', $token)->first();
        if($conf){
            if($conf->is_confirmed){
                return response()->json(['message' => 'confirmed already'], 202);
            }else{
                $conf->update([
                    $conf->is_confirmed = true
                ]);

                 // get user and update status
                $user = User::findOrFail($conf->user_id);
                $user->update([
                    $user->status = true
                ]);

                return response()->json(['message' => 'Verified'], 200);
            }
        }
    }

    public function updateProfile(Request $request){
        $this->validate($request, [
            'profile.first_name' => 'required|min:3|max:30',
            'profile.last_name' => 'required|min:3|max:30',
            'profile.phone' => 'required|max:14'
        ]);

        $user = auth('api')->user();
        $user->update([
            $user->first_name = $request->profile['first_name'],
            $user->last_name = $request->profile['last_name'],
            $user->phone = $request->profile['phone'],
        ]);

        return response()->json($user, 200);
    }

    public function updateProfilePicture(Request $request){
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        $user = auth('api')->user();
        // check if picture exists for profile, then unlink
        $old_pic = $user->picture;
        if($old_pic){
            $filePath = public_path('/images/profiles/users/'.$old_pic);
            if(file_exists($filePath)){
                unlink($filePath);
            }
        }

        // save file in folder...later in s3 when ready to deploy
        $file = $request->image;
        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz@&';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 8).".".$ext;

            //save new file in folder
            $file_loc = public_path('/images/profiles/users/'.$filename);
            if(in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'pdf'])){
                $upload = Image::make($file)->resize(220, 280, function($constraint){
                    $constraint->aspectRatio();
                });
                $upload->sharpen(2)->save($file_loc);
            }
        }

        // save path in db
        $user->update([
            $user->picture = $filename
        ]);

        return response()->json($user, 201);
    }

    public function confirmCurrentPassword(Request $request){
        $user = auth('api')->user();
        $current = $user->password;
        $check = Hash::check($request->password, $current);

        return response()->json($check, 200);
    }

    public function updateProfilePassword(Request $request){
        $this->validate($request, [
            'password' => 'required|min:5|max:20|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = auth('api')->user();
        $new = $request->password;

        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function submitUsersFeedback(Request $request){
        $this->validate($request, [
            'feedback.subject' => 'required|min:3|max:120',
            'feedback.body' => 'required|min:10|max:600',
        ]);

        if($request->feedback['sub_id']){
            $this->validate($request, [
                'feedback.sub_id' => 'min:3|max:8',
            ]);
            $target = $request->feedback['sub_id'];
        }elseif($request->feedback['expert']){
            $this->validate($request, [
                'feedback.expert' => 'min:3|max:20',
            ]);
            $target = $request->feedback['expert'];
        }else{
            $target = 'general';
        }

        $user = auth('api')->user();
        $admin = 9999999;

        $fb = new UsersFeedback;
        // $fb->user_id_from = $user->id;
        $fb->user_id = $user->id;
        $fb->user_id_to = $admin;
        $fb->is_parent = TRUE;
        $fb->parent_id = null;
        $fb->target = $target;
        $fb->subject = $request->feedback['subject'];
        $fb->body = $request->feedback['body'];
        $fb->save();

        // send email to user and admin
        Mail::to($user->email)->send(new FeedbackPostedEmail($user, $fb));
        Mail::to('banklan2010@gmail.com')->send(new FeedbackPostedEmail($user, $fb));

        return response()->json($fb, 200);
    }

    public function getUsersOutboxMsgs(){
        $user = auth('api')->user()->id;
        $msgs = UsersFeedback::where('user_id', $user)->where('is_deleted', false)->latest()->paginate(10);
        return response()->json($msgs, 200);
    }

    public function userDelOutboxMsg($id){
        $msg = UsersFeedback::findOrFail($id);

        $msg->update([
            $msg->is_deleted = true
        ]);

        $threads = UsersFeedback::where('parent_id', $id)->get();

        foreach($threads as $fb){
            $fb->update([
                $fb->is_deleted = true
            ]);
        }

        return response()->json($msg, 200);
    }

    public function getUsersInboxMsgs(){
        $user = auth('api')->user()->id;
        $msgs = UsersFeedback::where('user_id_to', $user)->where('is_deleted', false)->latest()->paginate(10);
        return response()->json($msgs, 200);
    }

    public function userDelInboxMsg($id){
        $msg = UsersFeedback::findOrFail($id);

        $msg->update([
            $msg->is_deleted = true
        ]);

        return response()->json($msg, 200);
    }

    public function getUserFeedback($id){
        $fb = UsersFeedback::where('is_deleted', false)->findOrFail($id);

        return response()->json($fb, 200);
    }

    public function updateFeedbackIsRead($id){
        $fb = UsersFeedback::findOrFail($id);

        $fb->update([
            $fb->is_read = true
        ]);

        return response()->json($fb, 200);
    }

    public function getFeedbackParent($id){
        $fb = UsersFeedback::where('is_deleted', false)->findOrFail($id);

        return response()->json($fb, 200);
    }

    public function getFeedbackThread($id){
        $fb = UsersFeedback::findOrFail($id);

        $thread = UsersFeedback::where('parent_id', $fb->parent_id)->where('is_deleted', false)->where('created_at', '<', $fb->created_at)->where('id', '!=', $id)->get();

        return response()->json($thread, 200);
    }

    public function getOutboxMsg($id){
        $msg = UsersFeedback::findOrFail($id);

        return response()->json($msg, 200);
    }

    public function replyFeedback(Request $request){
        $this->validate($request, [
            'reply.body' => 'required|min:3|max:300',
        ]);

        $user = auth('api')->user();
        $admin = 9999999;

        $fb = new UsersFeedback;
        $fb->user_id = $user->id;
        $fb->user_id_to = $admin;
        $fb->is_parent = False;
        $fb->parent_id = $request->reply['parent_id'];
        $fb->target = $request->reply['target'];
        $fb->subject = $request->reply['subject'];
        $fb->body = $request->reply['body'];
        $fb->save();

        $fb->fresh();
        // Mail::to('banklan2010@gmail.com')->send(new FeedbackPostedEmail($user, $fb));

        return response()->json(['feedback' => $fb, 'user'=> $user], 200);
    }

    public function sendEnquiry(Request $request){
        $this->validate($request, [
            'enquiry.fullname' => 'required|min:5|max:70',
            'enquiry.organization' => 'required|min:3|max:100',
            'enquiry.position' => 'min:2|max:30',
            'enquiry.email' => 'required|email',
            'enquiry.phone' => 'required|max:14',
            'enquiry.subject' => 'required|min:3|max:100',
            'enquiry.message' => 'required|min:5|max:500',
        ]);

        $enquiry = new Enquiry;
        $enquiry->fullname = $request->enquiry['fullname'];
        $enquiry->organization = $request->enquiry['organization'];
        $enquiry->position = $request->enquiry['position'];
        $enquiry->email = $request->enquiry['email'];
        $enquiry->phone = $request->enquiry['phone'];
        $enquiry->subject = $request->enquiry['subject'];
        $enquiry->message = $request->enquiry['message'];
        $enquiry->is_read = false;
        $enquiry->save();

        //send acknowledgement mail to user
        Mail::to($enquiry->email)->send(new EnquirySent($enquiry));

        //send mail with enquiry to lista
        Mail::to('philiafinancing@gmail.com')->send(new EnquiryMail($enquiry));

        return response()->json($enquiry, 200);
    }

    public function joinMailingList(Request $request){
        $this->validate($request, [
            'mail.f_name' => 'required|min:3|max:30',
            'mail.l_name' => 'required|min:3|max:30',
            'mail.email' => 'required|email|unique:mailing_list,email'
        ]);

        $ml = new MailingList;
        $ml->f_name = $request->mail['f_name'];
        $ml->l_name = $request->mail['l_name'];
        $ml->email = $request->mail['email'];
        $ml->save();

        return response()->json($ml, 200);
    }

    public function getOddPrice($odd){
        $plan = Plan::where('odd', $odd)->first();

        return response()->json($plan, 200);
    }

    public function getSubsrPaymentDetails($sub){
        $subscr = Subscription::where('sub_id', $sub)->first();
        $payment = Payment::where('subscription_id', $subscr->id)->first();

        return response()->json($payment, 200);
    }

    public function followExpert($expert){
        $expert = Expert::where('expert_id', $expert)->first();
        $user = auth('api')->user()->id;
        $follow = ExpertFollow::where('user_id', $user)->where('expert_id', $expert)->first();
        if($follow){
            return response()->json(['message' => 'Already followed'], 555);
        }else{
            $newFollow = new ExpertFollow;
            $newFollow->user_id = $user;
            $newFollow->expert_id = $expert->id;
            $newFollow->save();
            return response()->json(['message' => 'Success'], 201);
        }
    }

    public function checkFollow($expert){
        $expert = Expert::where('expert_id', $expert)->first();
        $user = auth('api')->user()->id;

        $is_followed = ExpertFollow::where('user_id', $user)->where('expert_id', $expert->id)->first();
        if($is_followed){
            return response()->json(['message' => true], 200);
        }else{
            return response()->json(['message' => false], 200);
        }
    }

    public function getFollowedExperts(){
        $user = auth('api')->user()->id;
        $follows = ExpertFollow::where('user_id', $user)->get();
        return response()->json($follows, 200);
    }
}
