#-------------------------------------------------
#
# Project created by QtCreator 2013-03-18T08:14:52
#
#-------------------------------------------------

QT       += core gui
QT       += network

greaterThan(QT_MAJOR_VERSION, 4): QT += widgets

TARGET = RPCWindow
TEMPLATE = app


SOURCES += main.cpp\
        mainwindow.cpp \
    logwindow.cpp \
    network.cpp \
    server.cpp \
    client.cpp

HEADERS  += mainwindow.h \
    logwindow.h \
    network.h \
    server.h \
    client.h

FORMS    += mainwindow.ui \
    logwindow.ui
