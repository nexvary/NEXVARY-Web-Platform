import type { RiskLevel } from '../components/ui/security-command';

export type SecuritySignal = {
  confidence: number;
  severity: number;
  exposure?: number;
  repeated?: boolean;
};

export type RiskAssessment = {
  score: number;
  level: RiskLevel;
};

export function assessRisk(signal: SecuritySignal): RiskAssessment {
  const confidence = clamp(signal.confidence, 0, 1);
  const severity = clamp(signal.severity, 0, 10);
  const exposure = clamp(signal.exposure ?? 0.5, 0, 1);
  const repeatBoost = signal.repeated ? 12 : 0;
  const score = Math.round(clamp((severity * 7.2 * confidence) + (exposure * 16) + repeatBoost, 0, 100));

  return { score, level: riskLevel(score) };
}

export function riskLevel(score: number): RiskLevel {
  const value = clamp(score, 0, 100);
  if (value >= 85) return 'critical';
  if (value >= 70) return 'high';
  if (value >= 45) return 'elevated';
  if (value >= 20) return 'guarded';
  return 'low';
}

function clamp(value: number, min: number, max: number) {
  return Math.min(max, Math.max(min, value));
}
