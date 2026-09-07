import type { ReactNode } from 'react';
import { motion } from 'framer-motion';
import { cn } from '../../lib/cn';

type Props = {
  children: ReactNode;
  className?: string;
  interactive?: boolean;
};

export function NxCard({ children, className, interactive = false }: Props) {
  return (
    <motion.article
      initial={{ opacity: 0, y: 8 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.28 }}
      whileHover={interactive ? { y: -3, scale: 1.005 } : undefined}
      className={cn(
        'relative overflow-hidden rounded-2xl border border-cyan-300/15 bg-slate-950/65 p-5 shadow-2xl shadow-black/15 backdrop-blur-xl',
        'before:pointer-events-none before:absolute before:inset-x-8 before:top-0 before:h-px before:bg-gradient-to-r before:from-transparent before:via-cyan-200/60 before:to-transparent',
        className,
      )}
    >
      {children}
    </motion.article>
  );
}
