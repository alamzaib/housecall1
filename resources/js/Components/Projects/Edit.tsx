import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ProjectForm, { ProjectFormData } from '@/Components/Projects/ProjectForm';
import { Project } from '@/types/project';

interface EditProps {
    project: Project;
}

export default function Edit({ project }: EditProps) {
    const { data, setData, put, processing, errors } = useForm<ProjectFormData>({
        name: project.name,
        description: project.description ?? '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('projects.update', project.id));
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Edit Project</h2>}
        >
            <Head title="Edit Project" />

            <div className="py-12">
                <div className="mx-auto max-w-2xl sm:px-6 lg:px-8">
                    <div className="bg-white p-6 shadow sm:rounded-lg">
                        <ProjectForm
                            data={data}
                            setData={setData}
                            errors={errors}
                            processing={processing}
                            onSubmit={submit}
                            submitLabel="Save Changes"
                        />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}