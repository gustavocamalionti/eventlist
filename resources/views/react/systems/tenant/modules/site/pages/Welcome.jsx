import { Link, Head } from "@inertiajs/react";
import NavbarSimple from "@reactTenant/modules/site/components/Navbar"

export default function Welcome({ text }) {
    return (
        <>
            <Head title="Welcome" />
            <NavbarSimple />
        </>
    );
}
