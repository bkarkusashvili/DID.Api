import * as React from "react";
import Box from "@mui/material/Box";
import Drawer from "@mui/material/Drawer";
import AppBar from "@mui/material/AppBar";
import Toolbar from "@mui/material/Toolbar";
import List from "@mui/material/List";
import Typography from "@mui/material/Typography";
import Divider from "@mui/material/Divider";
import {
    IconButton,
    ListItemButton,
    ListItemSecondaryAction,
    ListItemText,
} from "@mui/material";
import { AddCircle } from "@mui/icons-material";
import { Link } from "@inertiajs/inertia-react";

const drawerWidth = 240;
const menu = [
    // { text: 'Online შეკვეთები', model: 'order', canAdd: false },
    // { text: 'Offline შეკვეთები', model: 'invoice', canAdd: false },
    // {
    //     list: [
    //         { text: 'კურსები', model: 'course', canAdd: true },
    //         { text: 'Online ტრენინგი', model: 'livecourse', canAdd: true },
    //         { text: 'Offline ტრენინგი', model: 'offlinecourse', canAdd: true },
    //         { text: 'მასტერკლასი', model: 'coursevideo', canAdd: true },

    //     ]
    // },
    // {
    //     list: [
    //         { text: 'მომხმარებლები', model: 'user', canAdd: false },
    //         { text: 'შეფასებები', model: 'client', canAdd: true },
    //     ]
    // },
    // {
    //     list: [
    //         { text: 'გამოწერები', model: 'subscribe' },
    //         { text: 'გუნდი', model: 'team', canAdd: true },
    //         { text: 'მედია', model: 'media', canAdd: true },
    //     ]
    // },
    // {
    //     list: [
    //         { text: 'კატეგორია', model: 'category', canAdd: true },
    //         { text: 'ქალაქები', model: 'city', canAdd: true },
    //     ]
    // },
    // {
    //     list: [
    //         { text: 'ტრენერი', model: 'instructor', canAdd: true },
    //         { text: 'გვერდები', model: 'page', canAdd: false },
    //         { text: 'სლაიდერი', model: 'slider', canAdd: true },
    //         { text: 'პარამეტრები', model: 'option', canAdd: false },
    //     ]
    // },
    // { text: 'თარგმანი', model: 'translate', canAdd: false },
    { text: "კატეგორია", model: "category", canAdd: true },
    { text: "შიდა კატეგორია", model: "sub-category", canAdd: true },
    { text: "თემფლეითი", model: "template", canAdd: true },
];

const LocalListItem = ({ item }) => {
    return (
        <ListItemButton
            href={route(`${item.model}.index`)}
            LinkComponent={"li"}
            component={Link}
            className="admin-menu-item"
            selected={
                route().current(item.model) ||
                route().current(item.model + ".*")
            }
        >
            <ListItemText primary={item.text} />
            {item.canAdd && (
                <ListItemSecondaryAction>
                    <Link as="span" href={route(`${item.model}.create`)}>
                        <IconButton
                            color={"success"}
                            edge={"end"}
                            children={<AddCircle />}
                        />
                    </Link>
                </ListItemSecondaryAction>
            )}
        </ListItemButton>
    );
};

export const AdminLayout = ({ children }) => {
    return (
        <Box sx={{ display: "flex" }}>
            <AppBar
                position="fixed"
                sx={{ zIndex: (theme) => theme.zIndex.drawer + 1 }}
            >
                <Toolbar>
                    <Typography variant="h6" noWrap component="div">
                        Didge Admin Panel
                    </Typography>
                </Toolbar>
            </AppBar>
            <Drawer
                sx={{
                    width: drawerWidth,
                    flexShrink: 0,
                    "& .MuiDrawer-paper": {
                        width: drawerWidth,
                        boxSizing: "border-box",
                    },
                }}
                variant="permanent"
                anchor="left"
            >
                <Toolbar />
                <Divider />
                <List>
                    {menu.map((item, key) => {
                        if (!item.list) {
                            return <LocalListItem key={key} item={item} />;
                        }

                        return (
                            <React.Fragment key={key}>
                                <Divider />
                                {item.list.map((item, key) => (
                                    <LocalListItem key={key} item={item} />
                                ))}
                                <Divider />
                            </React.Fragment>
                        );
                    })}
                </List>
            </Drawer>
            <Box component="main" sx={{ flexGrow: 1, p: 3 }}>
                <Toolbar />
                {children}
            </Box>
        </Box>
    );
};
