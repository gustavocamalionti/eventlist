import * as React from "react";
import Box from "@mui/material/Box";
import Grid from "@mui/material/Grid";
import Typography from "@mui/material/Typography";

export default function Localization(props) {
    return (
        <Box component="section" sx={{ backgroundColor: "#110D0B", height: "20rem" }}>
            <Grid container spacing={2}>
                <Grid size={8}>
                    <Typography variant="h9" component="div" color="white">
                        Lorem ipsum ut justo aenean consequat aenean ac, sapien quam inceptos at senectus lectus,
                        imperdiet aliquam tellus suscipit congue metus. ut viverra libero malesuada odio cursus quam
                        vulputate mi tristique aenean at, himenaeos pharetra vitae feugiat nisi rhoncus condimentum
                        ullamcorper nullam.
                    </Typography>
                </Grid>
                <Grid size={4}>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d29366.313415023946!2d-47.23397446523439!3d-23.068189506154535!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94c8b3c0ca0ae561%3A0x36d777064999bf79!2sVilla%20Canton%20-%20Espa%C3%A7o%20para%20Eventos!5e0!3m2!1spt-BR!2sbr!4v1754163296855!5m2!1spt-BR!2sbr"
                        width="450"
                        height="295"
                        style={{ paddingTop: "20px" }}
                        loading="lazy"></iframe>
                </Grid>
            </Grid>
        </Box>
    );
}
