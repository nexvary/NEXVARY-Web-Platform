import { Link } from '@inertiajs/react';
import { Activity, FileText, Gauge, Languages, Search, Settings, ShieldCheck, Users } from 'lucide-react';

const items = [
  { label: 'Overview', href: '/secure-control/', icon: Gauge },
  { label: 'Audit', href: '/secure-control/audit', icon: Activity },
  { label: 'Content', href: '/secure-control/content', icon: FileText },
  { label: 'Languages', href: '/secure-control/languages', icon: Languages },
  { label: 'Users', href: '/secure-control/users', icon: Users },
  { label: 'SafeScan', href: '/secure-control/safescan', icon: Search },
  { label: 'Settings', href: '/secure-control/settings', icon: Settings },
];

export function AdminNav({ active = 'Overview' }: { active?: string }) {
  return (
    <nav aria-label="Administration" className="mb-7 overflow-x-auto rounded-2xl border border-cyan-300/10 bg-slate-950/60 p-2 backdrop-blur-xl">
      <div className="flex min-w-max items-center gap-1">
        <div className="mr-2 flex items-center gap-2 px-3 text-cyan-300">
          <ShieldCheck size={18} />
          <span className="text-xs font-black tracking-[0.2em]">CONTROL</span>
        </div>
        {items.map(({ label, href, icon: Icon }) => {
          const selected = label === active;
          return (
            <Link
              key={label}
              href={href}
              aria-current={selected ? 'page' : undefined}
              className={`flex min-h-11 items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold transition ${selected ? 'border border-cyan-300/20 bg-cyan-300/10 text-cyan-100' : 'text-slate-400 hover:bg-white/5 hover:text-white'}`}
            >
              <Icon size={16} />
              {label}
            </Link>
          );
        })}
      </div>
    </nav>
  );
}
