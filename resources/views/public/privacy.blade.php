@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'سياسة الخصوصية | NEXVARY' : 'Privacy Policy | NEXVARY')
@section('description', app()->getLocale() === 'ar' ? 'سياسة الخصوصية لموقع NEXVARY ونماذج التواصل والخدمات العامة.' : 'Privacy information for the NEXVARY website, public forms and product surfaces.')
@section('canonical', 'https://nexvary.com/privacy')

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-section nx-page-body">
    <div class="nx-section-title"><p>PRIVACY</p><h1>{{ $ar ? 'سياسة الخصوصية' : 'Privacy Policy' }}</h1><p class="nx-lead">{{ $ar ? 'نص واضح عن البيانات التي يعالجها الموقع العام وكيفية تقليل جمعها.' : 'A clear description of the data handled by the public website and how collection is minimized.' }}</p></div>
    <div class="nx-ssr-copy">
        @if($ar)
            <p>يجمع موقع NEXVARY فقط البيانات اللازمة لتشغيل الصفحات العامة والرد على طلبات التواصل. عند إرسال نموذج التواصل، قد نعالج الاسم والبريد ورقم الهاتف والجهة والدولة والرسالة وطريقة التواصل المفضلة. لا ترسل كلمات مرور أو مفاتيح API أو رموز تحقق أو بيانات دفع داخل النماذج العامة.</p>
            <p>قد تُحفظ سجلات تشغيل وأمان محدودة لحماية الموقع وتشخيص الأعطال، مع تقليل البيانات قدر الإمكان. بعض المنتجات مثل SafeScan توضح حدود المعالجة الخاصة بها داخل المنتج، ولا تعني هذه السياسة أن كل منتج يعالج البيانات بالطريقة نفسها.</p>
            <p>للاستفسارات المتعلقة بالخصوصية تواصل عبر info@nexvary.com.</p>
        @else
            <p>NEXVARY processes only the information needed to operate its public pages and respond to contact requests. A contact submission may include your name, email, phone number, organization, country, message and preferred contact method. Do not submit passwords, API keys, one-time codes or payment credentials through public forms.</p>
            <p>Limited operational and security logs may be retained to protect the website and diagnose failures, with data minimization applied where practical. Individual products, including SafeScan, may describe product-specific processing boundaries separately; this website policy does not imply that every product handles data in the same way.</p>
            <p>Privacy questions can be sent to info@nexvary.com.</p>
        @endif
    </div>
</main>
@endsection
