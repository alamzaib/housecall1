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

export interface DashboardStatistics {
    total_projects: number;
    total_tasks: number;
    completed_tasks: number;
    todo_tasks: number;
    in_progress_tasks: number;
}

export type TaskStatus = Task['status'];
export type TaskPriority = Task['priority'];
