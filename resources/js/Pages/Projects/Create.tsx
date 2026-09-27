import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ProjectForm, { ProjectFormData } from '@/Components/Projects/ProjectForm';

export default function Create() {
    const { data, setData, post, processing, errors } = useForm<ProjectFormData>({
        name: '',
        description: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('projects.store'));
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">New Project</h2>}
        >
            <Head title="New Project" />

            <div className="py-12">
                <div className="mx-auto max-w-2xl sm:px-6 lg:px-8">
                    <div className="bg-white p-6 shadow sm:rounded-lg">
                        <ProjectForm
                            data={data}
                            setData={setData}
                            errors={errors}
                            processing={processing}
                            onSubmit={submit}
                            submitLabel="Create Project"
                        />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
