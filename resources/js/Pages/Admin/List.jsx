import React, { useState } from "react";
import {
    Button,
    FormControl,
    InputLabel,
    MenuItem,
    Pagination,
    PaginationItem,
    Select,
    Stack,
    TextField,
} from "@mui/material";
import { Link, useForm } from "@inertiajs/inertia-react";
import DateAdapter from "@mui/lab/AdapterMoment";

import { AdminLayout } from "@/Layouts/AdminLayout";
import { DataGrid } from "@/Components";
import { DesktopDatePicker, LocalizationProvider } from "@mui/lab";

const getParam = (name) => route().params[name] || null;

const List = ({ rows, columns, model, paginate, search = [], sorting }) => {
    const initForm = {};
    search = search.map((item) => ({ ...item, value: getParam(item.name) }));
    search.forEach((item) => (initForm[item.name] = item.value));

    const [page, setPage] = useState(paginate.page);
    const [date, setDate] = useState();
    const pageChange = (e, value) => setPage(value);

    const isSelect = (item) => item.type === "select";
    const isDate = (item) => item.type === "date";

    const { setData, get, processing } = useForm(initForm);

    const submit = () => get(route(`${model}.index`));

    const getParams = (item) => {
        const params = route().params;
        delete params.lang;
        params.page = item.page;

        return params;
    };

    return (
        <AdminLayout>
            {!!search.length && (
                <Stack
                    spacing={2}
                    direction={"row"}
                    justifyContent={"space-between"}
                    alignItems={"center"}
                >
                    <Stack spacing={2} direction={"row"} marginBottom={2}>
                        {search.map((item, key) => (
                            <React.Fragment key={key}>
                                {isSelect(item) ? (
                                    <FormControl style={{ width: 200 }}>
                                        <InputLabel children={item.label} />
                                        <Select
                                            label={item.label}
                                            defaultValue={item.value}
                                            onChange={(e) =>
                                                setData(
                                                    item.name,
                                                    e.target.value
                                                )
                                            }
                                        >
                                            {item.options.map(
                                                (option, opKey) => (
                                                    <MenuItem
                                                        key={opKey}
                                                        value={option.value}
                                                        children={option.text}
                                                    />
                                                )
                                            )}
                                        </Select>
                                    </FormControl>
                                ) : isDate(item) ? (
                                    <LocalizationProvider
                                        dateAdapter={DateAdapter}
                                    >
                                        <DesktopDatePicker
                                            label={item.label}
                                            value={item.value || date}
                                            onChange={(moment) => {
                                                setDate(moment);
                                                setData(
                                                    "date",
                                                    moment.format("YYYY-MM-DD")
                                                );
                                            }}
                                            renderInput={(params) => (
                                                <TextField {...params} />
                                            )}
                                        />
                                    </LocalizationProvider>
                                ) : (
                                    <TextField
                                        label={item.label}
                                        defaultValue={item.value}
                                        type={item.type}
                                        onChange={(e) =>
                                            setData(item.name, e.target.value)
                                        }
                                    />
                                )}
                            </React.Fragment>
                        ))}
                    </Stack>
                    <Stack spacing={2} direction={"row"}>
                        <Button
                            disabled={processing}
                            onClick={() => submit()}
                            variant="contained"
                            children="გაფილტვრა"
                        />
                        <Button
                            disabled={processing}
                            href={route(`${model}.index`)}
                            LinkComponent={Link}
                            variant="outlined"
                            children="გასუფთავება"
                        />
                        {/* <Button
                            disabled={processing}
                            href={route(`${model}.export`, route().params)}
                            variant="contained"
                            children="გადმოწერა"
                        /> */}
                    </Stack>
                </Stack>
            )}
            <DataGrid
                model={model}
                rows={rows}
                columns={columns}
                sorting={sorting}
                pageSize={5}
                rowsPerPageOptions={[5]}
                checkboxSelection
            />
            {paginate.count > 1 && (
                <Pagination
                    style={{ marginTop: "30px" }}
                    page={page}
                    count={paginate.count}
                    onChange={pageChange}
                    color="primary"
                    renderItem={(item) => (
                        <PaginationItem
                            component={Link}
                            href={route(`${model}.index`, getParams(item))}
                            {...item}
                        />
                    )}
                />
            )}
        </AdminLayout>
    );
};

export default List;
