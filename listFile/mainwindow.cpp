#include "mainwindow.h"
#include "ui_mainwindow.h"
#include <iostream>
#include <QDir>
#include <QFileInfo>
#include <QDateTime>
#include <vector>
#include "fileinfo.h"

void    listFiles()
{
    QDir dir;
    dir.setFilter(QDir::Files | QDir::Hidden | QDir::NoSymLinks);
    dir.setSorting(QDir::Size | QDir::Reversed);

    std::vector<FileInfo *> listFile;

    QFileInfoList list = dir.entryInfoList();
    for (int i = 0; i < list.size(); ++i) {
        QFileInfo info = list.at(i);
        FileInfo * file = new FileInfo(info.fileName(), info.size(), info.lastModified());
        listFile.push_back(file);
    }

    std::vector<FileInfo *>::iterator it = listFile.begin();
    while (it != listFile.end())
    {
        std::cout << "name: " << qPrintable((*it)->getName()) << std::endl;
        std::cout << "size: " << (*it)->getSize() << std::endl;
        std::cout << "lastModified: " << qPrintable((*it)->getLastModified().toString()) << std::endl;
        std::cout << std::endl;
        ++it;
    }
}

MainWindow::MainWindow(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::MainWindow)
{
    ui->setupUi(this);
    listFiles();
}

MainWindow::~MainWindow()
{
    delete ui;
}

