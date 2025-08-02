import * as React from "react";
import AppBar from "@mui/material/AppBar";
import Box from "@mui/material/Box";
import Toolbar from "@mui/material/Toolbar";
import Typography from "@mui/material/Typography";
import IconButton from "@mui/material/IconButton";

export default function Navbar(props) {
    return (
        <Box sx={{ flexGrow: 1 }}>
            <AppBar position="static" sx={{ backgroundColor: props.color }}>
                <Toolbar>
                    <IconButton size="large" edge="start" color="inherit" aria-label="menu" sx={{ mr: 2 }}></IconButton>
                    <Typography variant="h6" component="div" sx={{ flexGrow: 1 }}>
                        {props.event}
                    </Typography>
                    <Typography variant="h8" component="div" sx={{ flexGrow: 1 }}>
                        {props.howWorks}
                    </Typography>
                    <Typography variant="h8" component="div" sx={{ flexGrow: 1 }}>
                        {props.localization}
                    </Typography>
                    <Typography variant="h8" component="div" sx={{ flexGrow: 1 }}>
                        {props.contacts}
                    </Typography>
                    <Typography variant="h8" component="div" sx={{ flexGrow: 1 }}>
                        {props.buyHere}
                    </Typography>
                    <Typography variant="h7" component="div" sx={{ flexGrow: 1 }}>
                        {props.system}
                    </Typography>
                </Toolbar>
            </AppBar>
        </Box>
    );
}
