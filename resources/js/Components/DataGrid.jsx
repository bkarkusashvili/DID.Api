import React, { useEffect, useRef, useState } from 'react';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';
import { Link, usePage } from '@inertiajs/inertia-react';
import { IconButton, Dialog, DialogTitle, DialogActions, Button, Checkbox } from '@mui/material';
import { Delete, Edit } from '@mui/icons-material';
import { useForm } from '@inertiajs/inertia-react'
import { Inertia, Method } from '@inertiajs/inertia';
import Sortable from 'sortablejs';

export const DataGrid = ({ rows, columns, model, sorting }) => {
    const isCheckbox = column => column.type === 'checkbox';

    const getValue = (column, row) => column.relation ? row[column.relation][column.field] : row[column.field];

    const [open, setOpen] = useState(false);
    const rowsRef = useRef(null);
    const [id, setId] = useState();
    const { actions } = usePage().props;
    const { delete: destroy, post, setData, data, get } = useForm();

    const handleOpen = id => {
        setOpen(true);
        setId(id);
    };
    const handleClose = () => setOpen(false);
    const handleDelete = () => {
        destroy(route(`${model}.destroy`, id), {
            onSuccess: () => {
                Inertia.reload()
            },
        });
        handleClose();
    };
    const columnUpdate = (id, column, value) => {
        post(route(`${model}.column`, { id, column, value: value ? 1 : 0 }));
    };

    useEffect(() => {
        if (rowsRef) {
            Sortable.create(rowsRef.current, {
                disabled: !sorting,
                onEnd: (e) => {
                    const data = { old: e.oldIndex, new: e.newIndex };

                    post(route(`${model}.updateRow`, data));
                }
            });
        }
    }, [rowsRef]);

    return (
        <TableContainer component={Paper}>
            <Table sx={{ minWidth: 650 }}>
                <TableHead>
                    <TableRow>
                        {columns.map((column, key) => (
                            <TableCell
                                key={key}
                                align={key === 0 ? 'left' : 'center'}
                                width={column.width}
                                style={{ fontWeight: 'bold' }}
                            >
                                {column.headerName}
                            </TableCell>
                        ))}
                        {(actions.edit || actions.delete) && (
                            <TableCell align={'right'} style={{ fontWeight: 'bold' }}>
                                მოქმედება
                            </TableCell>
                        )}
                    </TableRow>
                </TableHead>
                <TableBody ref={rowsRef}>
                    {rows.map(row => (
                        <TableRow
                            key={row.id}
                            className="data-row"
                            sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                        >
                            {columns.map((column, key) => (
                                <TableCell
                                    key={key}
                                    align={key === 0 ? 'left' : 'center'}
                                >
                                    {
                                        isCheckbox(column) ?
                                            <Checkbox
                                                readOnly={column.readOnly}
                                                defaultChecked={!!getValue(column, row)}
                                                onChange={e => columnUpdate(row.id, column.field, e.target.checked)}
                                            /> :
                                            getValue(column, row)
                                    }
                                </TableCell>
                            ))}
                            {(actions.edit || actions.delete) && (
                                <TableCell align="right">
                                    {actions.edit && (
                                        <Link href={route(`${model}.edit`, row.id)}>
                                            <IconButton color={'success'} edge={'end'} children={<Edit />} />
                                        </Link>
                                    )}
                                    {actions.delete && (
                                        <IconButton
                                            onClick={() => handleOpen(row.id)}
                                            color={'error'}
                                            edge={'end'}
                                            children={<Delete />}
                                        />
                                    )}
                                </TableCell>
                            )}
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
            <Dialog open={open} onClose={handleClose}>
                <DialogTitle children="მონაცემის წაშლა" />
                <DialogActions style={{ justifyContent: 'center' }}>
                    <Button onClick={handleClose} children="გაუქმება" />
                    <Button onClick={handleDelete} children="წაშლა" color={'error'} autoFocus />
                </DialogActions>
            </Dialog>
        </TableContainer>
    );
}
