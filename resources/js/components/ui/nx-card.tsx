import type { ReactNode } from 'react';
import { cn } from '../../lib/cn';

type Props = {
  children: ReactNode;
  className?: string;
  interactive?: boolean;
};

export function NxCard({ children, className, interactive = false }: Props) {
  return (
    <article
      className={cn(
        'relative overflow-hidden rounded-2xl border border-cyan-300/15 bg-slate-950/65 p-5 shadow-2xl shadow-black/15 backdrop-blur-xl',
        'before:pointer-events-none before:absolute before:inset-x-8 before:top-0 before:h-px before:bg-gradient-to-r before:from-transparent before:via-cyan-200/60 before:to-transparent',
        interactive && 'transition-transform hover:-translate-y-0.5',
        className,
      )}
    >
      {children}
    </article>
  );
}
