export interface Task {
    id: number;
    project_id: number;
    title: string;
    description: string | null;
    status: 'todo' | 'in_progress' | 'completed';
    priority: 'low' | 'medium' | 'high';
    created_at: string;
    updated_at: string;
}

export interface Project {
    id: number;
    user_id: number;
    name: string;
    description: string | null;
    tasks_count?: number;
    tasks?: Task[];
    created_at: string;
    updated_at: string;
}
