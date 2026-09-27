import { TaskPriority } from '@/types/project';

interface TaskPriorityBadgeProps {
    priority: TaskPriority;
}

const PRIORITY_STYLES: Record<TaskPriority, string> = {
    low: 'bg-slate-100 text-slate-600',
    medium: 'bg-amber-100 text-amber-700',
    high: 'bg-red-100 text-red-700',
};

export default function TaskPriorityBadge({ priority }: TaskPriorityBadgeProps) {
    return (
        <span className={`inline-flex rounded-full px-2 py-1 text-xs font-medium capitalize ${PRIORITY_STYLES[priority]}`}>
            {priority}
        </span>
    );
}
