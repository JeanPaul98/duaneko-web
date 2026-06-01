<x-mail::message>
<h1>Nous avons bien reçu votre demande de réinitialisation du mot de passe de votre compte</h1>
<p>Vous pouvez utiliser le code suivant pour récupérer votre compte
 :</p>
<!-- <x-mail::button :url="''">
Button Text
</x-mail::button> -->
{{$code}}
<br>Merci !
{{ config('app.name') }}
</x-mail::message>