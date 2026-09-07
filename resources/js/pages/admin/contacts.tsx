import { Head, router } from '@inertiajs/react';
import { Mail, MessageSquareText, Phone } from 'lucide-react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type ContactRequest = {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  company: string | null;
  country: string | null;
  reason: string;
  preferred_contact: string;
  message: string;
  status: string;
  created_at: string;
};

export default function Contacts({ requests }: { requests: ContactRequest[] }) {
  return (
    <>
      <Head title="Contact Inbox" />
      <main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8">
        <div className="mx-auto max-w-7xl">
          <AdminNav active="Contacts" />
          <div className="mb-6 flex items-center gap-3">
            <div className="rounded-2xl border border-cyan-300/20 bg-cyan-300/5 p-3 text-cyan-300"><MessageSquareText /></div>
            <div><h1 className="text-3xl font-black">Contact Inbox</h1><p className="text-sm text-slate-400">Requests submitted through the public Contact NEXVARY form.</p></div>
          </div>

          <div className="grid gap-4">
            {requests.length === 0 ? (
              <NxCard className="p-8 text-slate-500">No contact requests yet.</NxCard>
            ) : requests.map((request) => (
              <NxCard key={request.id} className="p-5">
                <div className="flex flex-wrap items-start justify-between gap-4">
                  <div>
                    <div className="flex flex-wrap items-center gap-2"><h2 className="text-lg font-bold">{request.name}</h2><span className={`rounded-full px-2 py-1 text-[11px] font-bold uppercase ${request.status === 'new' ? 'bg-cyan-300/10 text-cyan-200' : 'bg-white/5 text-slate-400'}`}>{request.status}</span></div>
                    <p className="mt-1 text-xs uppercase tracking-[0.16em] text-slate-500">{request.reason} · prefers {request.preferred_contact}</p>
                  </div>
                  <time className="text-xs text-slate-500">{request.created_at}</time>
                </div>

                <div className="mt-4 grid gap-2 text-sm text-slate-300 md:grid-cols-2">
                  <a href={`mailto:${request.email}`} className="inline-flex min-h-11 items-center gap-2 rounded-xl border border-white/5 bg-white/[0.02] px-3 hover:border-cyan-300/20"><Mail size={16} className="text-cyan-300" />{request.email}</a>
                  {request.phone && <a href={`tel:${request.phone}`} className="inline-flex min-h-11 items-center gap-2 rounded-xl border border-white/5 bg-white/[0.02] px-3 hover:border-cyan-300/20"><Phone size={16} className="text-cyan-300" />{request.phone}</a>}
                  {request.company && <div className="rounded-xl border border-white/5 bg-white/[0.02] px-3 py-3">Company: {request.company}</div>}
                  {request.country && <div className="rounded-xl border border-white/5 bg-white/[0.02] px-3 py-3">Country: {request.country}</div>}
                </div>

                <div className="mt-4 whitespace-pre-wrap rounded-2xl border border-white/5 bg-slate-900/70 p-4 text-sm leading-7 text-slate-200">{request.message}</div>

                {request.status === 'new' && (
                  <div className="mt-4 flex justify-end"><button type="button" onClick={() => router.post(`/secure-control/contacts/${request.id}/read`, {}, { preserveScroll: true })} className="min-h-11 rounded-xl border border-emerald-300/20 bg-emerald-300/10 px-4 text-sm font-bold text-emerald-200 hover:bg-emerald-300/15">Mark as read</button></div>
                )}
              </NxCard>
            ))}
          </div>
        </div>
      </main>
    </>
  );
}
