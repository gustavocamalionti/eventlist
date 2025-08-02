import * as React from "react";
import Box from "@mui/material/Box";
import Grid from "@mui/material/Grid";
import Typography from "@mui/material/Typography";
import eventLogo from "/resources/assets/systems/tenant/general/images/logo-cumpads.png";

export default function EventDescription(props) {
    return (
        <Box component="section" sx={{ backgroundColor: props.color, height: "24rem" }}>
            <Grid container spacing={2}>
                <Grid size={8}>
                    <img
                        src={eventLogo}
                        alt={props.eventName}
                        width="420"
                        height="420"
                        style={{ marginLeft: "6rem", paddingTop: "2rem" }}
                    />
                </Grid>
                <Grid size={4}>
                    <Typography variant="h4" component="div" color="white" style={{ paddingTop: "15px" }}>
                        COSTELÃO DOS CUMPADS
                    </Typography>
                    <Typography variant="h8" component="div" color="white" style={{ paddingTop: "15px" }}>
                        Localização
                    </Typography>
                    <Typography variant="h8" component="div" color="white" style={{ paddingTop: "15px" }}>
                        Data
                    </Typography>
                </Grid>
            </Grid>
        </Box>
    );
}
