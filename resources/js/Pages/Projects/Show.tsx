import { Head, Link, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import PrimaryButton from '@/Components/PrimaryButton';
import DangerButton from '@/Components/DangerButton';
import TaskCard from '@/Components/Tasks/TaskCard';
import { Project } from '@/types/project';

interface ShowProps {
    project: Project;
}

export default function Show({ project }: ShowProps) {
    const handleDelete = () => {
        if (confirm(`Delete "${project.name}"? This cannot be undone.`)) {
            router.delete(route('projects.destroy', project.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">{project.name}</h2>}
        >
            <Head title={project.name} />

            <div className="py-12">
                <div className="mx-auto max-w-3xl sm:px-6 lg:px-8">
                    <div className="bg-white p-6 shadow sm:rounded-lg">
                        <div className="flex items-start justify-between">
                            <div>
                                <h3 className="text-lg font-medium text-gray-900">{project.name}</h3>
                                <p className="mt-2 text-gray-600">
                                    {project.description || 'No description provided.'}
                                </p>
                            </div>
                            <div className="flex gap-3">
                                <Link href={route('projects.edit', project.id)}>
                                    <PrimaryButton>Edit</PrimaryButton>
                                </Link>
                                <DangerButton onClick={handleDelete}>Delete</DangerButton>
                            </div>
                        </div>

                        <div className="mt-8 border-t border-gray-200 pt-6">
                            <div className="mb-4 flex items-center justify-between">
                                <h4 className="text-sm font-medium uppercase text-gray-500">
                                    Tasks ({project.tasks?.length ?? 0})
                                </h4>
                                <Link href={route('tasks.create', project.id)}>
                                    <PrimaryButton>New Task</PrimaryButton>
                                </Link>
                            </div>

                            {project.tasks && project.tasks.length > 0 ? (
                                <ul className="divide-y divide-gray-200">
                                    {project.tasks.map((task) => (
                                        <TaskCard key={task.id} task={task} />
                                    ))}
                                </ul>
                            ) : (
                                <p className="text-gray-500">No tasks yet.</p>
                            )}
                        </div>
                    </div>

                    <div className="mt-4">
                        <Link href={route('projects.index')} className="text-sm text-gray-600 hover:text-gray-900">
                            ← Back to Projects
                        </Link>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
