interface TaskStatusChartProps {
    todo: number;
    inProgress: number;
    completed: number;
}

interface BarConfig {
    label: string;
    value: number;
    colorClass: string;
}

export default function TaskStatusChart({ todo, inProgress, completed }: TaskStatusChartProps) {
    const total = todo + inProgress + completed;

    const bars: BarConfig[] = [
        { label: 'To Do', value: todo, colorClass: 'bg-gray-400' },
        { label: 'In Progress', value: inProgress, colorClass: 'bg-blue-500' },
        { label: 'Completed', value: completed, colorClass: 'bg-green-500' },
    ];

    return (
        <div className="rounded-lg bg-white p-6 shadow">
            <h3 className="mb-4 text-sm font-medium uppercase text-gray-500">
                Task Status Distribution
            </h3>

            {total === 0 ? (
                <p className="text-gray-500">No tasks yet.</p>
            ) : (
                <div className="space-y-4">
                    {bars.map((bar) => {
                        const percentage = total > 0 ? Math.round((bar.value / total) * 100) : 0;

                        return (
                            <div key={bar.label}>
                                <div className="mb-1 flex items-center justify-between text-sm">
                                    <span className="text-gray-700">{bar.label}</span>
                                    <span className="text-gray-500">
                                        {bar.value} ({percentage}%)
                                    </span>
                                </div>
                                <div className="h-2 w-full overflow-hidden rounded-full bg-gray-100">
                                    <div
                                        className={`h-full rounded-full ${bar.colorClass}`}
                                        style={{ width: `${percentage}%` }}
                                    />
                                </div>
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
}
