import { Link, router } from '@inertiajs/react';
import { Task, TaskStatus } from '@/types/project';
import TaskStatusBadge from './TaskStatusBadge';
import TaskPriorityBadge from './TaskPriorityBadge';

interface TaskCardProps {
    task: Task;
}

export default function TaskCard({ task }: TaskCardProps) {
    const handleStatusChange = (status: TaskStatus) => {
        router.patch(route('tasks.status.update', task.id), { status });
    };

    const handleDelete = () => {
        if (confirm(`Delete task "${task.title}"?`)) {
            router.delete(route('tasks.destroy', task.id));
        }
    };

    return (
        <li className="flex items-center justify-between gap-4 py-3">
            <div className="min-w-0 flex-1">
                <p className="truncate font-medium text-gray-900">{task.title}</p>
                {task.description && (
                    <p className="truncate text-sm text-gray-500">{task.description}</p>
                )}
            </div>

            <TaskPriorityBadge priority={task.priority} />

            <select
                value={task.status}
                onChange={(e) => handleStatusChange(e.target.value as TaskStatus)}
                className="rounded-md border-gray-300 text-xs shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="todo">To Do</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>

            <TaskStatusBadge status={task.status} />

            <div className="flex gap-3 text-sm">
                <Link
                    href={route('tasks.edit', task.id)}
                    className="text-indigo-600 hover:text-indigo-900"
                >
                    Edit
                </Link>
                <button onClick={handleDelete} className="text-red-600 hover:text-red-900">
                    Delete
                </button>
            </div>
        </li>
    );
}
