# consultas de eliancrack

1) Obtener la cantidad de autos en venta.
    SELECT count(*) from autos;

2) obtener el promedio del precio de todos los autos.
    select avg(precio) from autos;

## Consultas de nosotros

1) crear una view para un administrativo de los primeros 5 autos mas cotizados.
    Create view top_5_autos AS
    Select
        id,
        marca,
        modelo,
        precio
    From
        autos
    Order by 
        precio DESC
    Limit 5;

    select * from top_5_autos

2) 
