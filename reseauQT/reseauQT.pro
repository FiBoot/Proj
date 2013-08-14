#-------------------------------------------------
#
# Project created by QtCreator 2013-07-25T13:27:48
#
#-------------------------------------------------

QT       += core gui
QT += network

greaterThan(QT_MAJOR_VERSION, 4): QT += widgets

TARGET = reseauQT
TEMPLATE = app


SOURCES += main.cpp\
        mainwindow.cpp \
    server.cpp \
    client.cpp \
    network.cpp

HEADERS  += mainwindow.h \
    server.h \
    client.h \
    network.h

FORMS    += mainwindow.ui
