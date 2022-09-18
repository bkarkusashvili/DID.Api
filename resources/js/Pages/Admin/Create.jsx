import React from "react";
import { AdminLayout } from "@/Layouts/AdminLayout";
import { Grid, Stack, Typography, Button } from "@mui/material";
import { useForm } from "@inertiajs/inertia-react";
import { Field } from "./Components";

const Create = ({ model, fields }) => {
    const initForm = {};
    fields.forEach((field) =>
        field.list
            .filter((item) => !item.disableCreate)
            .forEach((item) => (initForm[item.name] = item.value))
    );

    const { post, errors, processing, setData } = useForm(initForm);

    const submit = () => post(route(`${model}.store`));

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
                    children="ახლის დამატება"
                />
                <Button
                    variant="contained"
                    onClick={submit}
                    children="დამატება"
                    disabled={processing}
                />
            </Stack>
            <Grid container spacing={2}>
                {fields.map((field, key) => (
                    <Grid item key={key} xs={field.size}>
                        <Stack spacing={2}>
                            {field.list.map(
                                (item, key) =>
                                    !item.disableCreate && (
                                        <Field
                                            key={key}
                                            data={item}
                                            value={item.value || null}
                                            errors={errors}
                                            setChange={setData}
                                        />
                                    )
                            )}
                        </Stack>
                    </Grid>
                ))}
            </Grid>
        </AdminLayout>
    );
};

export default Create;
