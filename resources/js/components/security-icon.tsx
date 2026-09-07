type IconName = 'audio' | 'tower' | 'shield' | 'intel' | 'ai' | 'forensics';

const paths: Record<IconName, string> = {
  audio: 'M4 12h2m2-5v10m4-13v16m4-11v6m4-3h2',
  tower: 'M12 4v16m-4 0h8M9 9l3-5 3 5M6 7a8 8 0 0 0 0 10m12-10a8 8 0 0 1 0 10',
  shield: 'M12 3 5 6v5c0 5 3 8 7 10 4-2 7-5 7-10V6l-7-3Zm-3 9 2 2 4-5',
  intel: 'M4 12h16M12 4v16M6.5 6.5l11 11m0-11-11 11M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z',
  ai: 'M8 5h8v14H8zM5 8v8m14-8v8M10 9h.01M14 9h.01M10 14h4',
  forensics: 'M10 4a6 6 0 1 0 0 12 6 6 0 0 0 0-12Zm4.5 10.5L20 20M8 8h4m-4 3h4m-4 3h2',
};

export default function SecurityIcon({ name }: { name: IconName }) {
  return (
    <span className="nx-svg-icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round">
        <path d={paths[name]} />
      </svg>
    </span>
  );
}
