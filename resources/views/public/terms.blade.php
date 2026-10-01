@extends('public.layout')

@section('title', app()->getLocale() === 'ar' ? 'الشروط | NEXVARY' : 'Terms | NEXVARY')
@section('description', app()->getLocale() === 'ar' ? 'الشروط العامة لاستخدام موقع NEXVARY والمحتوى والمنتجات المنشورة.' : 'General terms for use of the NEXVARY website, public content and published product information.')
@section('canonical', 'https://nexvary.com/terms')

@section('content')
@php($ar = app()->getLocale() === 'ar')
<main class="nx-section nx-page-body">
    <div class="nx-section-title"><p>TERMS</p><h1>{{ $ar ? 'شروط الاستخدام' : 'Terms of Use' }}</h1><p class="nx-lead">{{ $ar ? 'شروط عامة لاستخدام الموقع ومعلومات المنتجات المنشورة.' : 'General conditions for use of the website and published product information.' }}</p></div>
    <div class="nx-ssr-copy">
        @if($ar)
            <p>المحتوى العام في NEXVARY مخصص للمعلومات والتعريف بالمنتجات والخدمات. حالة كل مشروع — مثل Available أو Beta أو Private Preview أو Internal — هي التي تحدد مستوى توفره، ولا يعني ظهوره في الموقع وجود حق تلقائي في التنزيل أو الوصول.</p>
            <p>لا يجوز استخدام الموقع أو الأدوات العامة في نشاط غير قانوني أو في محاولة للوصول غير المصرح به إلى أنظمة أو بيانات. الروابط والإصدارات والملفات الرسمية يجب الحصول عليها من قنوات NEXVARY المنشورة.</p>
            <p>قد تتغير خصائص المنتجات وحالة التوفر مع تطور الإصدارات. تواصل عبر info@nexvary.com للأسئلة المتعلقة بشروط الوصول إلى منتج محدد.</p>
        @else
            <p>Public NEXVARY content is provided for product, service and technical information. A project's stated lifecycle — such as Available, Beta, Private Preview or Internal — determines its availability; appearing on the website does not create an automatic right to download or access a product.</p>
            <p>The website and public tools must not be used for unlawful activity or unauthorized access to systems or data. Official releases, links and packages should be obtained through published NEXVARY channels.</p>
            <p>Product capabilities and availability may change as releases evolve. Questions about access terms for a specific product can be sent to info@nexvary.com.</p>
        @endif
    </div>
</main>
@endsection
