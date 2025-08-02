import { Link, Head } from "@inertiajs/react";
import NavbarSimple from "@reactTenant/modules/site/components/Navbar";
import { usePage } from "@inertiajs/react";

export default function Welcome({ text }) {
    const { auth, parameters, customizations } = usePage().props;
    return (
        <>
            <Head title="Welcome" />
            <NavbarSimple />
            <div className="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
                {text} <br /> <br />
                {/* {parameters.official_site} */}
                {customizations.styles.primaryColor}
                {/* {auth.user} */}
            </div>
        </>
    );
}
