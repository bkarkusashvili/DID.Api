import React, { useEffect, useState } from "react";
import { Editor } from "@tinymce/tinymce-react";
import {
    Divider,
    FormControl,
    FormControlLabel,
    FormGroup,
    FormHelperText,
    FormLabel,
    IconButton,
    InputAdornment,
    InputLabel,
    MenuItem,
    Select,
    Stack,
    Switch,
    TextField,
    Typography,
} from "@mui/material";
import DateAdapter from "@mui/lab/AdapterMoment";
import {
    DateTimePicker,
    DesktopDatePicker,
    LocalizationProvider,
} from "@mui/lab";
import { Button } from "@mui/material";
import { usePage } from "@inertiajs/inertia-react";
import { AddCircleOutlineOutlined, Delete } from "@mui/icons-material";
import { getError } from "./InputHelper";

export const Field = ({
    data,
    errors,
    value = null,
    setChange,
    group = null,
    relation = null,
    deleteFile,
}) => {
    const { base } = usePage().props;
    const [image, setImage] = useState(value ? `${base}/storage/${value}` : "");
    const [dateValue, setDateValue] = useState(value);
    const [error, setError] = useState();
    const isMultiline = data.type === "textarea";
    const isFile = data.type === "file";
    const isImage = data.type === "image";
    const isToggle = data.type === "toggle";
    const isSelect = data.type === "select";
    const isDate = data.type === "date";
    const isDateTime = data.type === "datetime";
    const isGroup = data.type === "group";

    console.log(isSelect, data.multiple, !value, value, data);

    const updateData = (value) =>
        setChange((prev) => {
            if (relation || data.relation) {
                if (group) {
                    prev[relation || data.relation][group][data.name] = value;
                } else {
                    prev[data.relation][data.name] = value;
                }
            } else {
                prev[data.name] = value;
            }

            return { ...prev };
        });

    const fileChange = (e) => {
        const file = e.target.files[0];
        updateData(file);

        setImage(URL.createObjectURL(file));
    };

    useEffect(
        () =>
            setError(
                getError(errors, relation || data.relation, group, data.name)
            ),
        [errors]
    );

    useEffect(() => {
        isSelect && data.multiline && !value ? [] : value;
    }, [value]);

    return isImage ? (
        <div className="image-input-wrap">
            <img src={image} />
            <Button
                variant="contained"
                onClick={(e) => e.target.nextSibling.click()}
                children="არჩევა"
            />
            <input type="file" onChange={fileChange} hidden />
        </div>
    ) : isFile ? (
        <>
            <FormLabel children={data.label} error={!!error} />
            <TextField
                variant="outlined"
                error={!!error}
                helperText={error}
                type="file"
                InputProps={{
                    endAdornment: value ? (
                        <InputAdornment position="end">
                            <IconButton
                                edge="end"
                                onClick={() => deleteFile(data.name)}
                            >
                                <Delete />
                            </IconButton>
                        </InputAdornment>
                    ) : null,
                }}
                onChange={fileChange}
                multiline={isMultiline}
                minRows={3}
                fullWidth
            />
        </>
    ) : isGroup ? (
        <>
            {data.divider === "top" && <Divider />}
            <Stack
                direction="row"
                justifyContent="space-between"
                alignItems="center"
            >
                <Typography
                    variant={"subtitle1"}
                    component="h3"
                    children={data.title}
                />
                <IconButton
                    color={"primary"}
                    onClick={() => addGroup(data.name, data.list)}
                    children={<AddCircleOutlineOutlined />}
                />
            </Stack>
            {data.list.map((item, key) => (
                <Field
                    key={key}
                    data={item}
                    errors={errors}
                    group={data.name}
                    relation={data.relation}
                    setChange={setChange}
                />
            ))}
            {data.divider === "top" && <Divider />}
        </>
    ) : isSelect ? (
        <FormControl fullWidth error={!!error}>
            <InputLabel>{data.label}</InputLabel>
            <Select
                disabled={data.disabled}
                defaultValue={value}
                label={data.label}
                multiple={data.multiple}
                onChange={(e) => updateData(e.target.value)}
            >
                {data.options?.map((option, key) => (
                    <MenuItem
                        key={key}
                        value={option.value}
                        children={option.text}
                    />
                ))}
            </Select>
            <FormHelperText children={error} />
        </FormControl>
    ) : isDate ? (
        <LocalizationProvider dateAdapter={DateAdapter}>
            <DesktopDatePicker
                disabled={data.disabled}
                label={data.label}
                value={dateValue}
                onChange={(moment) => {
                    setDateValue(moment);
                    updateData(moment.format("YYYY-MM-DD HH:mm:ss"));
                }}
                renderInput={(params) => (
                    <TextField {...params} error={!!error} helperText={error} />
                )}
            />
        </LocalizationProvider>
    ) : isDateTime ? (
        <LocalizationProvider dateAdapter={DateAdapter}>
            <DateTimePicker
                disabled={data.disabled}
                label={data.label}
                value={dateValue}
                onChange={(moment) => {
                    setDateValue(moment);
                    updateData(moment.format("YYYY-MM-DD HH:mm:ss"));
                }}
                renderInput={(params) => (
                    <TextField {...params} error={!!error} helperText={error} />
                )}
            />
        </LocalizationProvider>
    ) : isToggle ? (
        <FormControlLabel
            style={{ marginLeft: 0 }}
            disabled={data.disabled}
            control={
                <Switch
                    onChange={(e) => updateData(e.target.checked)}
                    defaultChecked={!!value}
                />
            }
            label={data.label}
        />
    ) : (
        // ) : isMultiline ? (
        //     <FormGroup>
        //         <FormLabel children={data.label} error={!!error} style={{ marginBottom: 8 }} />
        //         <Editor
        //             initialValue={value}
        //             onEditorChange={e => updateData(e)}
        //             too
        //             init={{
        //                 height: 300,
        //                 menubar: false,
        //                 plugins: [
        //                     'advlist lists',
        //                     'help'
        //                 ],
        //                 toolbar: 'undo redo | formatselect | ' +
        //                     'bold italic backcolor | alignleft aligncenter ' +
        //                     'alignright alignjustify | bullist numlist outdent indent | ' +
        //                     'removeformat | help',
        //                 content_style: 'body { font-family:FiraGO,sans-serif; }'
        //             }}
        //         />
        //         <FormHelperText error={!!error} children={error} style={{ marginTop: 8 }} />
        //     </FormGroup>
        <TextField
            disabled={data.disabled}
            variant="outlined"
            label={data.label}
            error={!!error}
            helperText={error}
            defaultValue={value}
            type={data.type}
            onChange={(e) => updateData(e.target.value)}
            multiline={isMultiline}
            minRows={3}
            fullWidth
        />
    );
};
