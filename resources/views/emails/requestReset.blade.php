@extends('emails/masterMail')
@section('mailbody')
Salam sejahtera <i>{{ $name }}</i>,
<br/>
<br/>
<p>Anda baru-baru ini meminta untuk menetapkan semula kata laluan anda untuk akaun {{ env('APP_NAME') }}. Klik pada pautan di bawah untuk mendapatkan kata laluan anda.</p>
<br/>
<p><a href='{{ url('/resetPass').'/'.urlencode($email).'/'.$actKey }}'>Pautan menetapkan semula kata laluan</a></p>
<br/><br/><br/>
<p>Jika anda tidak meminta penetapan semula kata laluan, abaikan e-mel ini. Tetapan semula kata laluan ini hanya sah selama 30 minit.</p>
<br/>
<p><i>Note: Ini adalah e-mel yang dihasilkan oleh sistem. Jangan balas e-mel ini.</i></p>
<br/><br/>
@endsection