interface StatCardProps {
    label: string;
    value: number;
}

export default function StatCard({ label, value }: StatCardProps) {
    return (
        <div className="rounded-lg bg-white p-6 shadow">
            <p className="text-sm font-medium text-gray-500">{label}</p>
            <p className="mt-2 text-3xl font-semibold text-gray-900">{value}</p>
        </div>
    );
}
