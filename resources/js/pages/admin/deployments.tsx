import { Head, router } from '@inertiajs/react';
import { ExternalLink, FileText, RefreshCw, Rocket, RotateCcw, Undo2 } from 'lucide-react';
import { AdminNav } from '../../components/admin/admin-nav';
import { NxCard } from '../../components/ui/nx-card';

type Project = {
  id: number;
  slug: string;
  name: string;
  repository_url?: string | null;
  repository_branch?: string | null;
  website_url?: string | null;
  health_url?: string | null;
  coolify_resource_uuid?: string | null;
  deploy_provider?: string | null;
  publish_to_website: boolean;
  is_published: boolean;
  lifecycle_status?: string | null;
  last_deployed_at?: string | null;
  last_commit_sha?: string | null;
};

type Run = {
  id: number;
  portfolio_app_id: number;
  project_name: string;
  provider: string;
  deployment_mode: string;
  environment: string;
  repository_url?: string | null;
  branch?: string | null;
  commit_sha?: string | null;
  domain?: string | null;
  remote_deployment_id?: string | null;
  status: string;
  health_status?: string | null;
  log_summary?: string | null;
  duration_ms?: number | null;
};

const shortSha = (sha?: string | null) => sha ? sha.slice(0, 10) : '—';

export default function Deployments({ projects, runs }: { projects: Project[]; runs: Run[] }) {
  const deploy = (project: Project, mode: 'deploy_publish' | 'deploy_only') => {
    router.post(`/secure-control/deployments/projects/${project.id}/deploy`, { deployment_mode: mode, force: false }, { preserveScroll: true });
  };

  return <>
    <Head title="Deployments" />
    <main className="min-h-screen bg-slate-950 px-4 py-7 text-white sm:px-8">
      <div className="mx-auto max-w-7xl">
        <AdminNav active="Deployments" />
        <div className="mb-6 flex items-center gap-3">
          <Rocket className="text-cyan-300" />
          <div>
            <h1 className="text-3xl font-black">Deployments</h1>
            <p className="text-sm text-slate-400">GitHub → CI → Coolify → HTTPS health verification → optional Our Work publication.</p>
          </div>
        </div>

        <div className="mb-8 grid gap-4 lg:grid-cols-2">
          {projects.map(project => <NxCard key={project.id} className="p-5">
            <div className="flex flex-wrap items-start justify-between gap-3">
              <div>
                <p className="text-xs font-black uppercase tracking-[.18em] text-cyan-300">{project.lifecycle_status || 'In Development'}</p>
                <h2 className="mt-1 text-xl font-black">{project.name}</h2>
                <p className="mt-1 break-all text-xs text-slate-500">{project.repository_url || 'Repository not configured'}</p>
              </div>
              <span className={`rounded-full border px-3 py-1 text-xs ${project.is_published ? 'border-emerald-300/20 text-emerald-200' : 'border-slate-500/20 text-slate-400'}`}>{project.is_published ? 'Published' : 'Private'}</span>
            </div>
            <div className="mt-4 grid gap-2 text-sm text-slate-300 sm:grid-cols-2">
              <span>Branch: {project.repository_branch || 'main'}</span>
              <span>Commit: {shortSha(project.last_commit_sha)}</span>
              <span>Provider: {project.deploy_provider || 'Coolify-ready'}</span>
              <span>Resource: {project.coolify_resource_uuid ? 'Configured' : 'Not configured'}</span>
            </div>
            <div className="mt-5 flex flex-wrap gap-2">
              <button disabled={!project.coolify_resource_uuid} onClick={() => deploy(project, 'deploy_publish')} className="inline-flex min-h-11 items-center gap-2 rounded-xl bg-cyan-300 px-4 font-black text-slate-950 disabled:cursor-not-allowed disabled:opacity-40"><Rocket size={16}/>Deploy & Publish</button>
              <button disabled={!project.coolify_resource_uuid} onClick={() => deploy(project, 'deploy_only')} className="inline-flex min-h-11 items-center gap-2 rounded-xl border border-cyan-300/20 px-4 text-cyan-100 disabled:cursor-not-allowed disabled:opacity-40"><Rocket size={16}/>Deploy Only</button>
              {project.website_url && <a className="inline-flex min-h-11 items-center gap-2 rounded-xl border border-white/10 px-4 text-slate-200" href={project.website_url} target="_blank" rel="noreferrer"><ExternalLink size={16}/>Open Project</a>}
              <button onClick={() => router.post(`/secure-control/deployments/projects/${project.id}/publication`, { published: !project.is_published }, { preserveScroll: true })} className="inline-flex min-h-11 items-center rounded-xl border border-white/10 px-4 text-slate-200">{project.is_published ? 'Unpublish' : 'Publish'}</button>
            </div>
          </NxCard>)}
        </div>

        <NxCard className="overflow-hidden">
          <div className="border-b border-white/10 p-5"><h2 className="text-xl font-black">Deployment history</h2></div>
          <div className="overflow-x-auto">
            <table className="w-full min-w-[1100px] text-left text-sm">
              <thead className="bg-white/[.03] text-xs uppercase tracking-wider text-slate-500"><tr>
                <th className="px-4 py-3">Project</th><th className="px-4 py-3">Branch / Commit</th><th className="px-4 py-3">Mode</th><th className="px-4 py-3">Environment</th><th className="px-4 py-3">Status</th><th className="px-4 py-3">Health</th><th className="px-4 py-3">Duration</th><th className="px-4 py-3">Actions</th>
              </tr></thead>
              <tbody>
                {runs.map(run => <tr key={run.id} className="border-t border-white/5 align-top">
                  <td className="px-4 py-4 font-bold">{run.project_name}</td>
                  <td className="px-4 py-4 text-slate-300">{run.branch || '—'}<div className="text-xs text-slate-500">{shortSha(run.commit_sha)}</div></td>
                  <td className="px-4 py-4 text-slate-300">{run.deployment_mode}</td>
                  <td className="px-4 py-4 text-slate-300">{run.environment}</td>
                  <td className="px-4 py-4"><span className="rounded-full border border-cyan-300/15 px-2 py-1 text-xs text-cyan-100">{run.status}</span></td>
                  <td className="px-4 py-4 text-slate-300">{run.health_status || '—'}</td>
                  <td className="px-4 py-4 text-slate-300">{run.duration_ms ? `${Math.round(run.duration_ms / 1000)}s` : '—'}</td>
                  <td className="px-4 py-4">
                    <div className="flex flex-wrap gap-2">
                      {run.remote_deployment_id && <button onClick={() => router.post(`/secure-control/deployments/${run.id}/refresh`, {}, { preserveScroll: true })} className="inline-flex min-h-11 items-center gap-1 rounded-lg border border-white/10 px-3"><RefreshCw size={14}/>Refresh</button>}
                      {run.status === 'Failed' && <button onClick={() => router.post(`/secure-control/deployments/${run.id}/retry`, {}, { preserveScroll: true })} className="inline-flex min-h-11 items-center gap-1 rounded-lg border border-amber-300/20 px-3 text-amber-200"><RotateCcw size={14}/>Retry</button>}
                      <button onClick={() => router.post(`/secure-control/deployments/${run.id}/rollback`, {}, { preserveScroll: true })} className="inline-flex min-h-11 items-center gap-1 rounded-lg border border-red-300/20 px-3 text-red-200"><Undo2 size={14}/>Rollback</button>
                    </div>
                    {run.log_summary && <details className="mt-2 max-w-md text-xs text-slate-500"><summary className="inline-flex min-h-11 cursor-pointer items-center gap-1 text-slate-400"><FileText size={13}/>Open Logs Summary</summary><p className="whitespace-pre-wrap pb-2">{run.log_summary}</p></details>}
                  </td>
                </tr>)}
                {runs.length === 0 && <tr><td colSpan={8} className="px-4 py-10 text-center text-slate-500">No deployment runs have been recorded yet.</td></tr>}
              </tbody>
            </table>
          </div>
        </NxCard>
      </div>
    </main>
  </>;
}
