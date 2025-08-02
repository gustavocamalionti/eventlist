import * as React from "react";
import Box from "@mui/material/Box";
import Grid from "@mui/material/Grid";
import Typography from "@mui/material/Typography";

export default function Footer(props) {
    return (
        <Box component="section" sx={{ backgroundColor: props.color, bottom: 0, left: 0 }}>
            <Grid size={12}>
                <Typography variant="h10" component="div" color="white" sx={{ flexGrow: 1 }}>
                    Desenvolvido por Among Tech
                </Typography>
            </Grid>
        </Box>
    );
}
