@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'تواصل مع نيكسفاري | المشاريع والأبحاث' : 'Contact Nexvary Inc | Projects & Research')
@section('description', app()->getLocale() === 'ar' ? 'تواصل مع نيكسفاري بخصوص تطوير البرمجيات والذكاء الاصطناعي والأمن الرقمي والأبحاث التطبيقية والشراكات ودعم المنتجات.' : 'Contact Nexvary Inc about software development, artificial intelligence, digital security, applied research, partnerships and product support.')
@section('canonical', 'https://nexvary.com/contact')

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main id="main-content" class="nx-section nx-page-body">
    <div class="nx-section-title"><p>CONTACT NEXVARY</p><h1>{{ $ar ? 'أخبرنا كيف يمكننا مساعدتك.' : 'Tell us how we can help.' }}</h1><p class="nx-lead">{{ $ar ? 'أرسل تفاصيل واضحة عن سبب التواصل دون مشاركة كلمات مرور أو مفاتيح سرية أو بيانات دفع.' : 'Send a clear description of your request without sharing passwords, secret keys or payment credentials.' }}</p></div>

    @if(session('contact_success'))<div class="nx-success" role="status">{{ $ar ? 'تم استلام طلبك بنجاح. شكرًا لتواصلك معنا.' : 'Your request was received successfully. Thank you for contacting us.' }}</div>@endif

    <form method="post" action="/contact" class="nx-public-form" >
        @csrf
        <div class="nx-form-grid">
            <label class="nx-field"><span>{{ $ar ? 'الاسم الكامل *' : 'Full name *' }}</span><input name="name" value="{{ old('name') }}" maxlength="120" required autocomplete="name">@error('name')<small class="nx-error">{{ $message }}</small>@enderror</label>
            <label class="nx-field"><span>{{ $ar ? 'البريد الإلكتروني *' : 'Email *' }}</span><input type="email" name="email" value="{{ old('email') }}" maxlength="255" required autocomplete="email">@error('email')<small class="nx-error">{{ $message }}</small>@enderror</label>
            <label class="nx-field"><span>{{ $ar ? 'رقم الهاتف' : 'Phone' }}</span><input name="phone" value="{{ old('phone') }}" maxlength="40" autocomplete="tel">@error('phone')<small class="nx-error">{{ $message }}</small>@enderror</label>
            <label class="nx-field"><span>{{ $ar ? 'الشركة / الجهة' : 'Company / Organization' }}</span><input name="company" value="{{ old('company') }}" maxlength="160" autocomplete="organization"></label>
            <label class="nx-field"><span>{{ $ar ? 'الدولة' : 'Country' }}</span><input name="country" value="{{ old('country') }}" maxlength="100" autocomplete="country-name"></label>
            <label class="nx-field"><span>{{ $ar ? 'سبب التواصل *' : 'Reason for contact *' }}</span><select name="reason" required>@foreach(['general'=>['General inquiry','استفسار عام'],'cybersecurity'=>['Cybersecurity','الأمن السيبراني'],'forensics'=>['Digital forensics','التحليل الجنائي الرقمي'],'privacy'=>['Privacy technology','تقنيات الخصوصية'],'partnership'=>['Partnership / Software project','شراكة / مشروع برمجي'],'support'=>['Product support','دعم المنتجات'],'other'=>['Other / Research','أخرى / أبحاث']] as $value=>$labels)<option value="{{ $value }}" @selected(old('reason','general')===$value)>{{ $ar ? $labels[1] : $labels[0] }}</option>@endforeach</select></label>
            <label class="nx-field nx-field-full"><span>{{ $ar ? 'طريقة التواصل المفضلة *' : 'Preferred contact method *' }}</span><select name="preferred_contact"><option value="email" @selected(old('preferred_contact','email')==='email')>{{ $ar ? 'البريد الإلكتروني' : 'Email' }}</option><option value="phone" @selected(old('preferred_contact')==='phone')>{{ $ar ? 'اتصال هاتفي' : 'Phone call' }}</option><option value="whatsapp" @selected(old('preferred_contact')==='whatsapp')>WhatsApp</option></select></label>
            <label class="nx-field nx-field-full"><span>{{ $ar ? 'رسالتك *' : 'Your message *' }}</span><textarea name="message" minlength="10" maxlength="5000" required>{{ old('message') }}</textarea>@error('message')<small class="nx-error">{{ $message }}</small>@enderror</label>
        </div>
        <label class="nx-honeypot" aria-hidden="true">Website<input name="website" tabindex="-1" autocomplete="off"></label>
        <div><button class="nx-btn nx-btn-primary" type="submit">{{ $ar ? 'إرسال الطلب' : 'Send request' }}</button></div>
    </form>

    <div class="nx-ssr-copy"><p>{{ $ar ? 'للمشاريع البرمجية، وضح المنصة المستهدفة والمشكلة التي تريد حلها والمرحلة الحالية. وللاستفسارات البحثية، اذكر المجال والنطاق المطلوب. يساعدنا ذلك على فهم احتياجاتك قبل مناقشة التفاصيل الفنية.' : 'For software projects, describe the target platform, the problem you want to solve and the current stage. For research inquiries, outline the subject and intended scope. This helps us understand the requirement before discussing implementation details.' }}</p><p>{{ $ar ? 'للاستفسارات المتعلقة بحادث أمني أو أدلة رقمية، اكتف بوصف عام دون إرسال ملفات حساسة. يمكنك التواصل مباشرة عبر info@nexvary.com. يتطلب تقييم الأجهزة والبيانات نطاقًا متفقًا عليه وتصريحًا مناسبًا.' : 'For security incidents or digital-evidence inquiries, start with a general description and avoid sending sensitive files. You can also email info@nexvary.com. Device and data evaluation requires an agreed scope and appropriate authorization.' }}</p><a class="nx-text-link" href="mailto:info@nexvary.com">info@nexvary.com</a></div>
</main>
@endsection
