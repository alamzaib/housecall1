import { Head, Link, router, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import PrimaryButton from '@/Components/PrimaryButton';
import { Project } from '@/types/project';
import { PageProps } from '@/types';

interface IndexProps {
    projects: Project[];
}

export default function Index({ projects }: IndexProps) {
    const { flash } = usePage<PageProps>().props;

    const handleDelete = (project: Project) => {
        if (confirm(`Delete "${project.name}"? This cannot be undone.`)) {
            router.delete(route('projects.destroy', project.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex items-center justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">Projects</h2>
                    <Link href={route('projects.create')}>
                        <PrimaryButton>New Project</PrimaryButton>
                    </Link>
                </div>
            }
        >
            <Head title="Projects" />

            <div className="py-12">
                <div className="mx-auto max-w-5xl sm:px-6 lg:px-8">
                    {flash?.success && (
                        <div className="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">
                            {flash.success}
                        </div>
                    )}

                    <div className="overflow-hidden bg-white shadow sm:rounded-lg">
                        {projects.length === 0 ? (
                            <p className="p-6 text-gray-500">
                                No projects yet. Create your first one to get started.
                            </p>
                        ) : (
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                            Name
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">
                                            Tasks
                                        </th>
                                        <th className="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-200 bg-white">
                                    {projects.map((project) => (
                                        <tr key={project.id}>
                                            <td className="px-6 py-4">
                                                <Link
                                                    href={route('projects.show', project.id)}
                                                    className="font-medium text-indigo-600 hover:text-indigo-900"
                                                >
                                                    {project.name}
                                                </Link>
                                            </td>
                                            <td className="px-6 py-4 text-gray-500">
                                                {project.tasks_count ?? 0}
                                            </td>
                                            <td className="px-6 py-4 text-right text-sm">
                                                <Link
                                                    href={route('projects.edit', project.id)}
                                                    className="mr-4 text-indigo-600 hover:text-indigo-900"
                                                >
                                                    Edit
                                                </Link>
                                                <button
                                                    onClick={() => handleDelete(project)}
                                                    className="text-red-600 hover:text-red-900"
                                                >
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}