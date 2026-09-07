import { Head, useForm, usePage } from '@inertiajs/react';
import { Building2, Mail, MessageSquareText, Phone, Send, ShieldCheck } from 'lucide-react';
import SiteShell from '../components/site-shell';

type ContactFormData = {
  name: string;
  email: string;
  phone: string;
  company: string;
  country: string;
  reason: string;
  preferred_contact: string;
  message: string;
  website: string;
};

export default function Contact() {
  const ar = document.documentElement.lang.startsWith('ar');
  const page = usePage<{ flash?: { contact_success?: boolean } }>();
  const form = useForm<ContactFormData>({
    name: '',
    email: '',
    phone: '',
    company: '',
    country: '',
    reason: 'general',
    preferred_contact: 'email',
    message: '',
    website: '',
  });

  const submit = (event: React.FormEvent) => {
    event.preventDefault();
    form.post('/contact', {
      preserveScroll: true,
      onSuccess: () => form.reset(),
    });
  };

  return (
    <SiteShell>
      <Head title={ar ? 'تواصل معنا | NEXVARY' : 'Contact NEXVARY'}>
        <meta name="description" content="Contact NEXVARY for cybersecurity, counter-surveillance, digital forensics, privacy technology, partnerships and support." />
        <link rel="canonical" href="https://nexvary.com/contact" />
      </Head>

      <main className="nx-section nx-page-body">
        <section className="mx-auto max-w-6xl">
          <div className="mb-8 grid gap-5 lg:grid-cols-[1.1fr_.9fr] lg:items-end">
            <div>
              <p className="nx-kicker">CONTACT NEXVARY</p>
              <h1 className="text-4xl font-black tracking-tight text-white sm:text-5xl">
                {ar ? 'أخبرنا كيف يمكننا مساعدتك.' : 'Tell us how we can help.'}
              </h1>
              <p className="mt-4 max-w-2xl text-base leading-8 text-slate-300">
                {ar
                  ? 'املأ النموذج ببياناتك وسبب التواصل، وسيتم حفظ طلبك داخل لوحة الإدارة لمراجعته من فريق NEXVARY.'
                  : 'Complete the form with your details and reason for contacting us. Your request will be stored securely for review by the NEXVARY team.'}
              </p>
            </div>
            <div className="rounded-2xl border border-cyan-300/15 bg-cyan-300/5 p-5 text-sm text-slate-300">
              <div className="mb-2 flex items-center gap-2 text-cyan-200"><ShieldCheck size={18} /><strong>{ar ? 'خصوصية الطلب' : 'Request privacy'}</strong></div>
              <p>{ar ? 'لا تطلب الصفحة كلمات مرور أو مفاتيح سرية أو بيانات دفع. تجنب إرسال معلومات شديدة الحساسية داخل الرسالة.' : 'This form never asks for passwords, secret keys, or payment credentials. Avoid including highly sensitive information in your message.'}</p>
            </div>
          </div>

          {page.props.flash?.contact_success && (
            <div role="status" className="mb-6 rounded-2xl border border-emerald-300/20 bg-emerald-300/10 px-5 py-4 text-emerald-100">
              {ar ? 'تم استلام طلبك بنجاح. شكرًا لتواصلك معنا.' : 'Your request was received successfully. Thank you for contacting us.'}
            </div>
          )}

          <form onSubmit={submit} className="grid gap-6 rounded-3xl border border-white/10 bg-slate-950/70 p-5 shadow-2xl shadow-cyan-950/20 sm:p-7" noValidate>
            <div className="grid gap-5 md:grid-cols-2">
              <label className="grid gap-2 text-sm text-slate-200">
                <span>{ar ? 'الاسم الكامل *' : 'Full name *'}</span>
                <input className="min-h-12 rounded-xl border border-white/10 bg-slate-900 px-4 text-white outline-none focus:border-cyan-300/50" value={form.data.name} onChange={(e) => form.setData('name', e.target.value)} autoComplete="name" required />
                {form.errors.name && <small className="text-rose-300">{form.errors.name}</small>}
              </label>

              <label className="grid gap-2 text-sm text-slate-200">
                <span>{ar ? 'البريد الإلكتروني *' : 'Email *'}</span>
                <div className="relative"><Mail className="pointer-events-none absolute left-3 top-3.5 text-slate-500" size={18} /><input type="email" className="min-h-12 w-full rounded-xl border border-white/10 bg-slate-900 px-4 pl-10 text-white outline-none focus:border-cyan-300/50" value={form.data.email} onChange={(e) => form.setData('email', e.target.value)} autoComplete="email" required /></div>
                {form.errors.email && <small className="text-rose-300">{form.errors.email}</small>}
              </label>

              <label className="grid gap-2 text-sm text-slate-200">
                <span>{ar ? 'رقم الهاتف' : 'Phone'}</span>
                <div className="relative"><Phone className="pointer-events-none absolute left-3 top-3.5 text-slate-500" size={18} /><input className="min-h-12 w-full rounded-xl border border-white/10 bg-slate-900 px-4 pl-10 text-white outline-none focus:border-cyan-300/50" value={form.data.phone} onChange={(e) => form.setData('phone', e.target.value)} autoComplete="tel" /></div>
                {form.errors.phone && <small className="text-rose-300">{form.errors.phone}</small>}
              </label>

              <label className="grid gap-2 text-sm text-slate-200">
                <span>{ar ? 'الشركة / الجهة' : 'Company / Organization'}</span>
                <div className="relative"><Building2 className="pointer-events-none absolute left-3 top-3.5 text-slate-500" size={18} /><input className="min-h-12 w-full rounded-xl border border-white/10 bg-slate-900 px-4 pl-10 text-white outline-none focus:border-cyan-300/50" value={form.data.company} onChange={(e) => form.setData('company', e.target.value)} autoComplete="organization" /></div>
              </label>

              <label className="grid gap-2 text-sm text-slate-200">
                <span>{ar ? 'الدولة' : 'Country'}</span>
                <input className="min-h-12 rounded-xl border border-white/10 bg-slate-900 px-4 text-white outline-none focus:border-cyan-300/50" value={form.data.country} onChange={(e) => form.setData('country', e.target.value)} autoComplete="country-name" />
              </label>

              <label className="grid gap-2 text-sm text-slate-200">
                <span>{ar ? 'سبب التواصل *' : 'Reason for contact *'}</span>
                <select className="min-h-12 rounded-xl border border-white/10 bg-slate-900 px-4 text-white outline-none focus:border-cyan-300/50" value={form.data.reason} onChange={(e) => form.setData('reason', e.target.value)}>
                  <option value="general">{ar ? 'استفسار عام' : 'General inquiry'}</option>
                  <option value="cybersecurity">{ar ? 'الأمن السيبراني' : 'Cybersecurity'}</option>
                  <option value="tscm">{ar ? 'مكافحة التجسس الفني TSCM' : 'Counter-surveillance / TSCM'}</option>
                  <option value="forensics">{ar ? 'التحليل الجنائي الرقمي' : 'Digital forensics'}</option>
                  <option value="privacy">{ar ? 'الخصوصية والحماية الرقمية' : 'Privacy technology'}</option>
                  <option value="partnership">{ar ? 'شراكة أو تعاون' : 'Partnership'}</option>
                  <option value="support">{ar ? 'دعم فني' : 'Support'}</option>
                  <option value="other">{ar ? 'سبب آخر' : 'Other'}</option>
                </select>
              </label>

              <label className="grid gap-2 text-sm text-slate-200 md:col-span-2">
                <span>{ar ? 'طريقة التواصل المفضلة *' : 'Preferred contact method *'}</span>
                <select className="min-h-12 rounded-xl border border-white/10 bg-slate-900 px-4 text-white outline-none focus:border-cyan-300/50" value={form.data.preferred_contact} onChange={(e) => form.setData('preferred_contact', e.target.value)}>
                  <option value="email">Email</option>
                  <option value="phone">{ar ? 'اتصال هاتفي' : 'Phone call'}</option>
                  <option value="whatsapp">WhatsApp</option>
                </select>
              </label>

              <label className="grid gap-2 text-sm text-slate-200 md:col-span-2">
                <span>{ar ? 'رسالتك وسبب الرغبة في التواصل *' : 'Your message and reason for contacting us *'}</span>
                <div className="relative"><MessageSquareText className="pointer-events-none absolute left-3 top-4 text-slate-500" size={18} /><textarea rows={7} className="w-full rounded-xl border border-white/10 bg-slate-900 px-4 py-3 pl-10 text-white outline-none focus:border-cyan-300/50" value={form.data.message} onChange={(e) => form.setData('message', e.target.value)} required /></div>
                <div className="flex justify-between gap-4"><small className="text-slate-500">{ar ? 'من 10 إلى 5000 حرف.' : '10–5000 characters.'}</small><small className="text-slate-500">{form.data.message.length}/5000</small></div>
                {form.errors.message && <small className="text-rose-300">{form.errors.message}</small>}
              </label>
            </div>

            <label className="absolute -left-[10000px] top-auto h-px w-px overflow-hidden" aria-hidden="true">
              Website
              <input tabIndex={-1} autoComplete="off" value={form.data.website} onChange={(e) => form.setData('website', e.target.value)} />
            </label>

            <div className="flex flex-wrap items-center justify-between gap-4 border-t border-white/5 pt-5">
              <p className="max-w-2xl text-xs leading-6 text-slate-500">{ar ? 'بالإرسال، أنت توافق على استخدام بيانات النموذج فقط لمعالجة طلب التواصل والرد عليه.' : 'By submitting, you agree that the form data may be used to process and respond to your request.'}</p>
              <button type="submit" disabled={form.processing} className="inline-flex min-h-12 items-center gap-2 rounded-xl border border-cyan-300/30 bg-cyan-300/10 px-6 font-bold text-cyan-100 transition hover:bg-cyan-300/20 disabled:cursor-not-allowed disabled:opacity-50">
                <Send size={18} />
                {form.processing ? (ar ? 'جارٍ الإرسال…' : 'Sending…') : (ar ? 'إرسال الطلب' : 'Send request')}
              </button>
            </div>
          </form>
        </section>
      </main>
    </SiteShell>
  );
}
