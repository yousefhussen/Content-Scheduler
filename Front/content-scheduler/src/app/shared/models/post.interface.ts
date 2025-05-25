
export interface Post {
    id: number;
    title: string;
    scheduled_time: string;
    status: 'draft' | 'scheduled' | 'published';
    platforms: string[];
}