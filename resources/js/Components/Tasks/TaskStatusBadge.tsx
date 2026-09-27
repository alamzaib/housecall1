import { TaskStatus } from '@/types/project';

interface TaskStatusBadgeProps {
    status: TaskStatus;
}

const STATUS_STYLES: Record<TaskStatus, string> = {
    todo: 'bg-gray-100 text-gray-700',
    in_progress: 'bg-blue-100 text-blue-700',
    completed: 'bg-green-100 text-green-700',
};

const STATUS_LABELS: Record<TaskStatus, string> = {
    todo: 'To Do',
    in_progress: 'In Progress',
    completed: 'Completed',
};

export default function TaskStatusBadge({ status }: TaskStatusBadgeProps) {
    return (
        <span className={`inline-flex rounded-full px-2 py-1 text-xs font-medium ${STATUS_STYLES[status]}`}>
            {STATUS_LABELS[status]}
        </span>
    );
}
