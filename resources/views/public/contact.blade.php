@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'تواصل مع NEXVARY للأمن التقني' : 'Contact NEXVARY Security Team')
@section('description', app()->getLocale() === 'ar' ? 'تواصل مع NEXVARY بخصوص الأمن السيبراني ومكافحة التجسس الفني والتحليل الجنائي الرقمي وتقنيات الخصوصية والشراكات.' : 'Contact NEXVARY for cybersecurity, counter-surveillance, digital forensics, privacy technology, partnerships and technical support.')
@section('canonical', 'https://nexvary.com/contact')

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-section nx-page-body">
    <div class="nx-section-title"><p>CONTACT NEXVARY</p><h1>{{ $ar ? 'أخبرنا كيف يمكننا مساعدتك.' : 'Tell us how we can help.' }}</h1><p class="nx-lead">{{ $ar ? 'أرسل تفاصيل واضحة عن سبب التواصل دون مشاركة كلمات مرور أو مفاتيح سرية أو بيانات دفع.' : 'Send a clear description of your request without sharing passwords, secret keys or payment credentials.' }}</p></div>

    @if(session('contact_success'))<div class="nx-success" role="status">{{ $ar ? 'تم استلام طلبك بنجاح. شكرًا لتواصلك معنا.' : 'Your request was received successfully. Thank you for contacting us.' }}</div>@endif

    <form method="post" action="/contact" class="nx-public-form" novalidate>
        @csrf
        <div class="nx-form-grid">
            <label class="nx-field"><span>{{ $ar ? 'الاسم الكامل *' : 'Full name *' }}</span><input name="name" value="{{ old('name') }}" maxlength="120" required autocomplete="name">@error('name')<small class="nx-error">{{ $message }}</small>@enderror</label>
            <label class="nx-field"><span>{{ $ar ? 'البريد الإلكتروني *' : 'Email *' }}</span><input type="email" name="email" value="{{ old('email') }}" maxlength="255" required autocomplete="email">@error('email')<small class="nx-error">{{ $message }}</small>@enderror</label>
            <label class="nx-field"><span>{{ $ar ? 'رقم الهاتف' : 'Phone' }}</span><input name="phone" value="{{ old('phone') }}" maxlength="40" autocomplete="tel">@error('phone')<small class="nx-error">{{ $message }}</small>@enderror</label>
            <label class="nx-field"><span>{{ $ar ? 'الشركة / الجهة' : 'Company / Organization' }}</span><input name="company" value="{{ old('company') }}" maxlength="160" autocomplete="organization"></label>
            <label class="nx-field"><span>{{ $ar ? 'الدولة' : 'Country' }}</span><input name="country" value="{{ old('country') }}" maxlength="100" autocomplete="country-name"></label>
            <label class="nx-field"><span>{{ $ar ? 'سبب التواصل *' : 'Reason for contact *' }}</span><select name="reason" required>@foreach(['general'=>'General inquiry','cybersecurity'=>'Cybersecurity','tscm'=>'Counter-surveillance / TSCM','forensics'=>'Digital forensics','privacy'=>'Privacy technology','partnership'=>'Partnership','support'=>'Support','other'=>'Other'] as $value=>$label)<option value="{{ $value }}" @selected(old('reason','general')===$value)>{{ $label }}</option>@endforeach</select></label>
            <label class="nx-field nx-field-full"><span>{{ $ar ? 'طريقة التواصل المفضلة *' : 'Preferred contact method *' }}</span><select name="preferred_contact"><option value="email" @selected(old('preferred_contact','email')==='email')>Email</option><option value="phone" @selected(old('preferred_contact')==='phone')>{{ $ar ? 'اتصال هاتفي' : 'Phone call' }}</option><option value="whatsapp" @selected(old('preferred_contact')==='whatsapp')>WhatsApp</option></select></label>
            <label class="nx-field nx-field-full"><span>{{ $ar ? 'رسالتك *' : 'Your message *' }}</span><textarea name="message" minlength="10" maxlength="5000" required>{{ old('message') }}</textarea>@error('message')<small class="nx-error">{{ $message }}</small>@enderror</label>
        </div>
        <label class="nx-honeypot" aria-hidden="true">Website<input name="website" tabindex="-1" autocomplete="off"></label>
        <div><button class="nx-btn nx-btn-primary" type="submit">{{ $ar ? 'إرسال الطلب' : 'Send request' }}</button></div>
    </form>

    <div class="nx-ssr-copy" style="margin-top:34px">@if($ar)<p>استخدم هذه الصفحة لطلب معلومات عن خدمة أمنية، مشروع تقني، شراكة، أو دعم مرتبط بمنتجات NEXVARY. كلما كان الوصف محددًا، كان من الأسهل توجيه الطلب إلى المسار المناسب. لا ترسل كلمات مرور أو رموز تحقق أو مفاتيح API أو بيانات بطاقات أو نسخًا من مواد شديدة الحساسية داخل النموذج العام.</p><p>في الطلبات المتعلقة بحادث أمني أو دليل رقمي، اذكر نوع البيئة والمشكلة العامة فقط إلى أن يتم تحديد قناة مناسبة للتعامل مع التفاصيل الحساسة. وفي طلبات مكافحة التجسس الفني، اذكر نوع الموقع أو المركبة أو البيئة المطلوب تقييمها دون كشف معلومات تشغيلية لا لزوم لها في المرحلة الأولى.</p>@else<p>Use this page for questions about a security service, a technical project, a partnership, or support related to NEXVARY products. A specific description helps route the request to the appropriate workflow. Do not submit passwords, one-time codes, API secrets, payment-card data, or copies of highly sensitive material through the public contact form.</p><p>For an active security incident or digital-evidence matter, describe the general environment and problem first so that an appropriate handling channel can be established before sensitive details are exchanged. For counter-surveillance requests, identify the type of office, room, vehicle, device set, or RF environment that requires assessment without exposing unnecessary operational information at the initial contact stage.</p>@endif</div>
</main>
@endsection
