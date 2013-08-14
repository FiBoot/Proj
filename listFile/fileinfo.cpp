#include "fileinfo.h"

FileInfo::FileInfo()
{
    name = "";
    size = 0;
    lastModified = QDateTime();
}

FileInfo::FileInfo(QString fileName, int fileSize, QDateTime date)
{
    name = fileName;
    size = fileSize;
    lastModified = date;
}

FileInfo::~FileInfo()
{
}

QString FileInfo::getName()
{
    return (name);
}

int FileInfo::getSize()
{
    return (size);
}

QDateTime   FileInfo::getLastModified()
{
    return (lastModified);
}

void    FileInfo::setName(QString fileName)
{
    name = fileName;
}

void    FileInfo::setSize(int fileSize)
{
    size = fileSize;
}

void    FileInfo::setLastModified(QDateTime date)
{
    lastModified = date;
}
