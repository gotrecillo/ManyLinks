@component('mail::message')
# Please confirm your email address

You have created a ManyLinks account

@component('mail::button', ['url' => route('auth.email-verification.check', ['code' => $user->confirmation_code]).'?email='.urlencode($user->email)])
    Confirm your email
@endcomponent

@endcomponent
