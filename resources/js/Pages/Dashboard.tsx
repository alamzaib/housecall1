import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import StatCard from '@/Components/Dashboard/StatCard';
import TaskStatusChart from '@/Components/Dashboard/TaskStatusChart';
import { DashboardStatistics } from '@/types/project';

interface DashboardProps {
    statistics: DashboardStatistics;
}

export default function Dashboard({ statistics }: DashboardProps) {
    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                        <StatCard label="Total Projects" value={statistics.total_projects} />
                        <StatCard label="Total Tasks" value={statistics.total_tasks} />
                        <StatCard label="Completed" value={statistics.completed_tasks} />
                        <StatCard label="To Do" value={statistics.todo_tasks} />
                        <StatCard label="In Progress" value={statistics.in_progress_tasks} />
                    </div>

                    <TaskStatusChart
                        todo={statistics.todo_tasks}
                        inProgress={statistics.in_progress_tasks}
                        completed={statistics.completed_tasks}
                    />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
