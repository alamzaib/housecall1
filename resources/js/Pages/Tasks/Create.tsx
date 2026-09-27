import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import TaskForm, { TaskFormData } from '@/Components/Tasks/TaskForm';
import { Project } from '@/types/project';

interface CreateProps {
    project: Project;
}

export default function Create({ project }: CreateProps) {
    const { data, setData, post, processing, errors } = useForm<TaskFormData>({
        title: '',
        description: '',
        status: 'todo',
        priority: 'medium',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('tasks.store', project.id));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    New Task — {project.name}
                </h2>
            }
        >
            <Head title="New Task" />

            <div className="py-12">
                <div className="mx-auto max-w-2xl sm:px-6 lg:px-8">
                    <div className="bg-white p-6 shadow sm:rounded-lg">
                        <TaskForm
                            data={data}
                            setData={setData}
                            errors={errors}
                            processing={processing}
                            onSubmit={submit}
                            submitLabel="Create Task"
                        />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
