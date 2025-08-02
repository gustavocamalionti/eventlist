import * as React from "react";
import { Head, usePage } from "@inertiajs/react";
import Navbar from "@reactTenant/modules/site/components/home/Navbar";
import EventDescription from "@reactTenant/modules/site/components/home/EventDescription";
import Localization from "@reactTenant/modules/site/components/home/Localization";
import BuyTickets from "@reactTenant/modules/site/components/home/BuyTickets";
import Footer from "@reactTenant/modules/site/components/home/Footer";

export default function Home({ text }) {
    const { auth, parameters, customizations } = usePage().props;
    //{customizations.styles.primaryColor}
    return (
        <React.Fragment>
            <Head title={text} />
            <Navbar
                event={text}
                howWorks={"COMO FUNCIONA"}
                localization={"LOCALIZAÇÃO"}
                buyHere={"COMPRE AQUI"}
                contacts={"CONTATOS"}
                system={"EVENT LIST"}
                color={"#110D0B"}
            />
            <EventDescription eventName={parameters.page_title} color={"#351C15"} />
            <Localization />
            <BuyTickets color="white" />
            <Footer color={"#110D0B"} />
        </React.Fragment>
    );
}
