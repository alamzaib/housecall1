import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import TaskForm, { TaskFormData } from '@/Components/Tasks/TaskForm';
import { Task } from '@/types/project';

interface EditProps {
    task: Task;
}

export default function Edit({ task }: EditProps) {
    const { data, setData, put, processing, errors } = useForm<TaskFormData>({
        title: task.title,
        description: task.description ?? '',
        status: task.status,
        priority: task.priority,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('tasks.update', task.id));
    };

    return (
        <AuthenticatedLayout
            header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Edit Task</h2>}
        >
            <Head title="Edit Task" />

            <div className="py-12">
                <div className="mx-auto max-w-2xl sm:px-6 lg:px-8">
                    <div className="bg-white p-6 shadow sm:rounded-lg">
                        <TaskForm
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
