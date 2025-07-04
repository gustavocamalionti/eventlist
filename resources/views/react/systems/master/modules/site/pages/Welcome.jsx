import { Link, Head } from "@inertiajs/react";

export default function Welcome({ text }) {
    return (
        <>
            <Head title="Oi Mariana" />
            <div className="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
                {text}
            </div>
        </>
    );
}
