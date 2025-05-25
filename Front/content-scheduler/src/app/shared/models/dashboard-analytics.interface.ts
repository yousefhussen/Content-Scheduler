import { PlatformStat } from "./platform-stat.interface";

export interface DashboardAnalytics {
    posts_per_platform: PlatformStat[];
    publishing_success_rate: number;
    scheduled_count: number;
    published_count: number;
    scheduled_today: number;
    draft_count: number;
  }