import { TableContainer, Table, TableCell, TableRow, TableHead, Paper, TableBody, IconButton, Box } from "@mui/material";
import React from "react";
import { ShoppingCartItem } from "../hooks/useShoppingCart";
import ClearIcon from "@mui/icons-material/Clear";

export default function ShoppingCartTable( {removeItemFromShoppingCart, shoppingCart} ) {

    const totalQuantity = shoppingCart?.items?.reduce((total, item) => total + item.quantity, 0) ?? 0;
    const totalPrice = shoppingCart?.items?.reduce((total, item) => total + item.quantity * item.product.price, 0) ?? 0;

    return (
        <TableContainer>
            <Table>
                <TableHead>
                    <TableRow>
                        <TableCell>Produit</TableCell>
                        <TableCell>Quantité</TableCell>
                        <TableCell>Prix</TableCell>
                        <TableCell></TableCell>
                    </TableRow>
                </TableHead>

                <TableBody>
                    {shoppingCart?.items.map((item: ShoppingCartItem) => (
                        <TableRow key={item.product.id}>
                        <TableCell>
                            <Box display="flex" flexDirection="row" alignItems="center">
                                <img 
                                    style={{marginRight: "20px"}}
                                    width={100} 
                                    height={100} 
                                    src={`/images/products/${item.product.imageName}`}
                                    alt={item.product.name} 
                                />
                                <span>{item.product.name} </span>
                            </Box>
                        </TableCell>
                        <TableCell>{item.quantity}</TableCell> 
                        <TableCell>{item.product.price} €</TableCell>
                        <TableCell>
                            <IconButton onClick={() => removeItemFromShoppingCart(item.product)}>
                                <ClearIcon />
                            </IconButton>
                        </TableCell>
                    </TableRow>
                    ))}
                     <TableRow>
                        <TableCell>Total</TableCell>
                        <TableCell>{totalQuantity}</TableCell>
                        <TableCell>{totalPrice.toFixed(2)} €</TableCell>
                        <TableCell /> {/* Cette cellule est vide pour correspondre aux autres lignes */}
                    </TableRow>
                </TableBody>
            </Table>
        </TableContainer>
    )
}