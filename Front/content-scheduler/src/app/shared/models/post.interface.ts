
export interface Post {
    id?: number; // Optional for new posts
    title: string;
    message_content: string;
    images: File[];
    scheduled_time: string; // e.g., "2026-05-22 14:30:00"
    status: 'draft' | 'scheduled' | 'published'; // or just string if you want it open
    platforms: number[]; // Array of platform IDs
}