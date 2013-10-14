<?php

class PasswordController extends BaseController
{

    /**
     * Reset the current user's password.
     * @return Redirect
     */
    public function postReset()
    {
        $current_user = Auth::user();

        if (!Auth::validate(array(
                    'username' => Auth::user()->username,
                    'password' => Input::get('current_password')
                )))
            return Redirect::back()
                            ->with('flash_error', 'Your current password does not match, please <a href="#password"> try again</a>!');
        $validator = Validator::make(
                        array('new_password' => Input::get('new_password'), 'new_password_confirmation' => Input::get('new_password_conf')), array('new_password' => 'required|min:5|confirmed')
        );
        if ($validator->passes()) {
            $current_user->password = Hash::make(Input::get('new_password'));
            $current_user->save();
            return Redirect::back()
                            ->with('flash_success', 'Your password has been updated successfully!');
        }
        return Redirect::back()
                        ->with('flash_error', 'Your new passwords do not match or your new password does not meet the minimum length five characters, please <a href="#password"> try again</a>!');
    }

}

?>
