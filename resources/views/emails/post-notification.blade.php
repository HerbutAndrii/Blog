<x-mail::message>
# {{ $name }} has published a new post!
 
You can check it out at the link below!
 
<x-mail::button :url="$url">
View Post
</x-mail::button>
 
</x-mail::message>