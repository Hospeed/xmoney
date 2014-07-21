/*
 * X-Money - Gestao Empresarial Integrada
 *
 * Copyright (C) 2010 Eneias Ramos de Melo <neneias@gmail.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


#ifndef __ImpressaoGeral_H__
#define __ImpressaoGeral_H__


#include <cairo/cairo.h>


#define WIDTH_POINTS 842
#define HEIGHT_POINTS 595


int impressao_geral (cairo_t *cr, char *sql);

extern double TamanhoFonte;
extern double LarguraPagina;
extern double AlturaPagina;


#endif /* __ImpressaoGeral_H__ */
