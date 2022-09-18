import React from "react";
import { AdminLayout } from "@/Layouts/AdminLayout";
import { Grid, Stack, Typography, Button } from "@mui/material";
import { Inertia } from "@inertiajs/inertia";
import { Link, useForm } from "@inertiajs/inertia-react";
import { Field } from "./Components";

const Files = new Set(["file", "image", "video"]);

const Edit = ({ model, data, fields }) => {
    const initForm = { _method: "PUT" };
    fields.forEach((field) =>
        field.list.forEach((item) => {
            const key = item.name;
            const value = Files.has(item.type) ? null : data[item.name];

            if (item.relation) {
                if (!initForm[item.relation]) {
                    initForm[item.relation] = {};
                }
                initForm[item.relation][key] =
                    data[item.relation] && !Files.has(item.type)
                        ? data[item.relation][item.name]
                        : null;
            } else {
                initForm[key] = value;
            }
        })
    );

    const {
        post,
        errors,
        processing,
        data: formData,
        setData,
    } = useForm(initForm);

    const submit = (close = false) =>
        post(route(`${model}.update`, data.id), {
            onSuccess: (e) => close && history.back(),
        });
    const deleteFile = (file) =>
        Inertia.post(
            route(`${model}.deleteFile`, data.id),
            { file },
            { preserveScroll: true }
        );

    return (
        <AdminLayout>
            <Stack
                direction="row"
                justifyContent="space-between"
                alignItems="center"
                mt={2}
                mb={4}
            >
                <Typography
                    variant="h4"
                    component="h3"
                    children="რედაქტირება"
                />
                <Stack spacing={2} direction={"row"}>
                    <Button
                        variant="contained"
                        onClick={() => submit(false)}
                        children="შენახვა"
                        disabled={processing}
                    />
                    <Button
                        variant="contained"
                        onClick={() => submit(true)}
                        children="შენახვა და დახურვა"
                        disabled={processing}
                    />
                    <Button
                        component={Link}
                        href={route(`${model}.index`)}
                        variant="contained"
                        children="გაუქმება"
                        disabled={processing}
                    />
                </Stack>
            </Stack>
            <Grid container spacing={2}>
                {fields.map((field, key) => (
                    <Grid item key={key} xs={field.size}>
                        <Stack spacing={2}>
                            {field.list.map((item, key) => (
                                <Field
                                    key={key}
                                    data={item}
                                    errors={errors}
                                    value={
                                        !item.relation
                                            ? data[item.name]
                                            : data[item.relation]
                                            ? data[item.relation][item.name]
                                            : null
                                    }
                                    setChange={setData}
                                    deleteFile={deleteFile}
                                />
                            ))}
                        </Stack>
                    </Grid>
                ))}
            </Grid>
        </AdminLayout>
    );
};

export default Edit;
